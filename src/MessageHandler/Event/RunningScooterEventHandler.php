<?php

namespace App\MessageHandler\Event;

use App\Service\{
    BaseService,
    ScooterService
};
use App\Entity\Scooter;
use Codeception\Util\HttpCode;
use Doctrine\ORM\EntityManagerInterface;
use App\Message\Event\RunningScooterEvent;
use App\Exception\ScooterMalfunctioningException;
use Symfony\Component\Messenger\MessageBusInterface;
use App\Interfaces\EventHandler\EventHandlerInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

/**
 * Class RunningScooterEventHandler
 *
 * @package App\MessageHandler\Event
 */
class RunningScooterEventHandler implements MessageHandlerInterface, EventHandlerInterface
{
    /**
     * An amount minutes in an hour's
     */
    private const MINUTES = 60;

    /**
     * It appears to be a factor for transformation km into longitude,
     * so called ratio. (Needs to be heavily checked, though)
     */
    private const DELIMITER = 100000;

    /**
     * @var MessageBusInterface
     */
    private $eventBus;

    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * @var ScooterService
     */
    private $scooterService;

    /**
     * @var UrlGeneratorInterface
     */
    private $router;

    /**
     * @var TokenStorageInterface
     */
    private $tokenStorage;

    /**
     * DeployScootersHandler constructor.
     *
     * @param MessageBusInterface $eventBus
     * @param ScooterService $scooterService
     * @param EntityManagerInterface $entityManager
     * @param UrlGeneratorInterface $router
     * @param TokenStorageInterface $tokenStorage
     */
    public function __construct(
        MessageBusInterface $eventBus,
        ScooterService $scooterService,
        EntityManagerInterface $entityManager,
        UrlGeneratorInterface $router,
        TokenStorageInterface $tokenStorage
    ) {
        $this->router = $router;
        $this->eventBus = $eventBus;
        $this->entityManager = $entityManager;
        $this->scooterService = $scooterService;
        $this->tokenStorage = $tokenStorage;
    }

    public function __invoke(RunningScooterEvent $event)
    {
        /** @var $scooter Scooter  */
        $scooter = $this->entityManager->getRepository(Scooter::class)->findOneBy(
            ['id' => $event->getScooterId()]
        );

        // Let's assume here that 1 second equals 1 minute of traveling,
        // since we are not going to waste any of our time and wait for updates...
        $diff = (new \DateTime())->diff($scooter->getLocation()->getUpdatedAt())->s;
        $factor = ($event->getScooterVelocity() *
            $diff) /
            self::DELIMITER;

        $meta = stream_get_contents($scooter->getMetadata());
        if ($meta & 0b0001) {
            $factor *= -1;
        }

        $this->scooterService->setBodyFields(
            [
            'updatedAt'=> (new \DateTime())->format(DATE_ATOM)
        ] + (
                // currently we are moving by line so...
                ($meta & 0b0100) ?
                [
                    self::UNITS_OF_COORDINATES[0] => (string)$scooter->getLocation()->getLatitude(),
                    self::UNITS_OF_COORDINATES[1] => (string)($scooter->getLocation()->getAdjustedValue(
                        $factor,
                        self::UNITS_OF_COORDINATES[1],
                        $meta
                    ))
                ] :
                [
                    self::UNITS_OF_COORDINATES[0] => (string)($scooter->getLocation()->getAdjustedValue(
                        $factor,
                        self::UNITS_OF_COORDINATES[0],
                        $meta
                    )),
                    self::UNITS_OF_COORDINATES[1] => (string)$scooter->getLocation()->getLongitude()
                ]
            )
        );

        /** @var $scooter Scooter */
        $this->scooterService->addResouceIdentifier($this->router->generate(
            'scooter-update-location',
            ['id' => $scooter->getId()]
        ));

        // so far have only one token for every scooter so that will do...
        $this->scooterService->setAuthHeader(
            $scooter->getApiTokens()->current()->getToken()
        );

        $response = $this->scooterService->requestAsync(
            $this->scooterService->makeBody(),
            BaseService::SYMFONY_URL_HOLDER
        );

        if ($response['responseCode'] == HttpCode::CREATED) {
            // although we do can create a new queue and apply the time delay there...
            sleep(random_int(1, 3));
            $this->eventBus->dispatch(
                new RunningScooterEvent(
                    $scooter->getId(),
                    $event->getScooterVelocity()
                )
            );
        } else if ($response['responseCode'] != HttpCode::NO_CONTENT) {
            // there is something wrong with a particular scooter
            throw new ScooterMalfunctioningException($scooter->getId());
        }
    }
}
