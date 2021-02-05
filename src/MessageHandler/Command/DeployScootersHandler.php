<?php

namespace App\MessageHandler\Command;

use App\Entity\Scooter;
use App\Service\BaseService;
use App\Service\ScooterService;
use Codeception\Util\HttpCode;
use App\Message\Command\DeployScooters;
use Doctrine\ORM\EntityManagerInterface;
use App\Message\Event\RunningScooterEvent;
use App\Exception\ScooterMalfunctioningException;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Messenger\Handler\MessageSubscriberInterface;

/**
 * Class DeployScootersHandler
 *
 * @package App\MessageHandler\Command
 */
class DeployScootersHandler implements MessageSubscriberInterface
{
    /**
     * A distance will be measured by usual V*t
     * and here is a set of velocity in km/h
     */
    private const INITIAL_VELOCITY = [
        //nasty driver's
        40,
        // adolescent driver's
        25,
        // careful driver's
        15,
        // elderly driver's
        5
    ];

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

    /**
     * Triggers an appropriate event
     *
     * @param DeployScooters $deployScooters
     */
    public function __invoke(DeployScooters $deployScooters)
    {
        $scooters = $this->entityManager->getRepository(Scooter::class)->findBy(
            ['occupied' => false],
            null,
            $deployScooters->getNumberOfScooters()
        );

        $velocityData = $this->getVelocity();

        $this->scooterService->setBodyFields([
           'occupied' => true,
           'updatedAt'=> (new \DateTime())->format(DATE_ATOM)
        ]);

        /** @var  $scooter Scooter */
        foreach ($scooters as $key => $scooter) {
            $this->scooterService->addResouceIdentifier($this->router->generate(
                'scooter-update-status',
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

            if ($response['responseCode'] == HttpCode::OK) {
                $this->eventBus->dispatch(
                    new RunningScooterEvent(
                        $scooter->getId(),
                        $velocityData[$key]
                    )
                );
            } else {
                // there is something wrong with a particular scooter
                throw new ScooterMalfunctioningException($scooter->getId());
            }

            sleep(1);
        }
    }

    /**
     * Gets additional configuration parameters
     *
     * @return iterable
     */
    public static function getHandledMessages(): iterable
    {
        yield DeployScooters::class => [
            'method' => '__invoke',
            'priority' => 10,
            //'from_transport' => 'async'
        ];
    }

    /**
     * Adds some more entropy into velocity data
     *
     * @return int[]
     * @throws \Exception
     */
    private function getVelocity(): array
    {
        $initialData = self::INITIAL_VELOCITY;

        do {
            $initialData[] = random_int(5, 40);
        } while (count($initialData) < 10);

        return $initialData;
    }
}
