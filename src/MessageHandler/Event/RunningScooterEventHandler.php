<?php

namespace App\MessageHandler\Event;

use App\Entity\Scooter;
use App\Exception\ScooterMalfunctioningException;
use App\Service\BaseService;
use App\Service\ScooterService;
use Codeception\Util\HttpCode;
use Doctrine\ORM\EntityManagerInterface;
use App\Message\Event\RunningScooterEvent;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class RunningScooterEventHandler implements MessageHandlerInterface
{
    /**
     * Explicitly for testing reasons'
     */
    private const INITIAL_LATITUDE = 43.6532;

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
     * DeployScootersHandler constructor.
     *
     * @param MessageBusInterface $eventBus
     * @param ScooterService $scooterService
     * @param EntityManagerInterface $entityManager
     * @param UrlGeneratorInterface $router
     */
    public function __construct(
        MessageBusInterface $eventBus,
        ScooterService $scooterService,
        EntityManagerInterface $entityManager,
        UrlGeneratorInterface $router
    ) {
        $this->router = $router;
        $this->eventBus = $eventBus;
        $this->entityManager = $entityManager;
        $this->scooterService = $scooterService;
    }

    public function __invoke(RunningScooterEvent $event)
    {
        /** @var $scooter Scooter  */
        $scooter = $this->entityManager->getRepository(Scooter::class)->findOneBy(
            ['id' => $event->getScooterId()]
        );

        $factor = 1/$event->getScooterVelocity();
        $this->scooterService->setBodyFields([
            'latitude' => (string)($scooter->getLocation()->getLatitude() + $factor),
            'longitude' => (string)($scooter->getLocation()->getLongitude() + $factor),
            'updatedAt'=> (new \DateTime())->format(DATE_ATOM)
        ]);

        /** @var  $scooter Scooter */
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
            // Explicitly for testing reasons'
            if ($scooter->getLocation()->getLatitude() < self::INITIAL_LATITUDE + $factor * 10) {
                $this->eventBus->dispatch(
                    new RunningScooterEvent(
                        $scooter->getId(),
                        $event->getScooterVelocity()
                    )
                );
            }
        } else {
            // there is something wrong with a particular scooter
            throw new ScooterMalfunctioningException($scooter->getId());
        }

        sleep(1);
    }
}
