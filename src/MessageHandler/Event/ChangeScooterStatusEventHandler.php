<?php

namespace App\MessageHandler\Event;

use App\Exception\DeployScooterException;
use App\Service\{
    BaseService,
    ScooterService
};
use App\Entity\Scooter;
use Codeception\Util\HttpCode;
use App\Message\Command\DeployScooters;
use Doctrine\ORM\EntityManagerInterface;
use App\Message\Event\ChangeScooterStatusEvent;
use App\Exception\ScooterMalfunctioningException;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

/**
 * Class ChangeScooterStatusEventHandler
 *
 * @package App\MessageHandler\Event
 */
class ChangeScooterStatusEventHandler implements MessageHandlerInterface
{
    /**
     * A required amount of scooters to deploy
     * if there is a need to do so
     */
    private const SCOOTER_AMOUNT = 1;

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
     * @var MessageBusInterface
     */
    private $messageBus;

    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * ChangeScooterStatusEventHandler constructor.
     *
     * @param UrlGeneratorInterface $router
     * @param ScooterService $scooterService
     * @param TokenStorageInterface $tokenStorage
     * @param MessageBusInterface $messageBus
     */
    public function __construct(
        UrlGeneratorInterface $router,
        ScooterService $scooterService,
        TokenStorageInterface $tokenStorage,
        MessageBusInterface $messageBus,
        EntityManagerInterface $entityManager
    ) {
        $this->router = $router;
        $this->messageBus = $messageBus;
        $this->tokenStorage = $tokenStorage;
        $this->scooterService = $scooterService;
        $this->entityManager = $entityManager;
    }

    /**
     * @param ChangeScooterStatusEvent $event
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function __invoke(ChangeScooterStatusEvent $event)
    {
        $scooter = $this->entityManager->getRepository(Scooter::class)->findOneBy(
            ['id' => $event->getScooterId()]
        );

        $this->scooterService->setBodyFields([
            'occupied' => $event->getScooterStatus()
        ]);

        $this->scooterService->addResouceIdentifier($this->router->generate(
            'scooter-update-status',
            ['id' => $event->getScooterId()]
        ));

        $this->scooterService->setAuthHeader(
            $scooter->getApiTokens()->current()->getToken()
        );

        $response = $this->scooterService->requestAsync(
            $this->scooterService->makeBody(),
            BaseService::SYMFONY_URL_HOLDER
        );

        if ($response['responseCode'] == HttpCode::OK) {
            try {
                // let's say that a scooter will be unoccupied for 5-10 seconds
                sleep(random_int(5, 10));
                $this->messageBus->dispatch(new DeployScooters(self::SCOOTER_AMOUNT));
            } catch (\Exception $e) {
                throw new DeployScooterException($e);
            }
        } else {
            // there is something wrong with a particular scooter
            throw new ScooterMalfunctioningException($scooter->getId());
        }
    }
}
