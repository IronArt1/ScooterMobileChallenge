<?php

namespace App\Service;

use App\Message\Event\ChangeScooterStatusEvent;
use App\Entity\{
    Scooter,
    Location
};
use Codeception\Util\HttpCode;
use App\Repository\ScooterRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Interfaces\Service\ScooterServiceInterface;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * Class ScooterService
 *
 * @package App\Service
 */
class ScooterService extends BaseService implements ScooterServiceInterface
{
    /**
     * @var EntityManagerInterface
     */
    private $em;

    /**
     * @var ScooterRepository
     */
    private $scooterRepository;

    /**
     * ScooterService constructor.
     *
     * @param array $data
     * @param EntityManagerInterface $em
     * @param ScooterRepository $scooterRepository
     */
    public function __construct(
        array $data,
        EntityManagerInterface $em,
        ScooterRepository $scooterRepository
    ) {
        // in a real App there is a need of such `data` every time
        // so just skipping those cases where there is no need
        parent::__construct($data);

        $this->em = $em;
        $this->scooterRepository = $scooterRepository;
    }

    /**
     * @inheritDoc
     */
    public function scooterUpdateStatus(
        Scooter $scooter,
        bool $status
    ): void {
        $scooter->setOccupied($status);
        $this->em->persist($scooter);
        $this->em->flush();
    }

    /**
     * @inheritDoc
     */
    public function scooterUpdateLocation(
        $content,
        Scooter $scooter,
        SerializerInterface $serializer,
        ValidatorInterface $validator,
        &$response,
        &$statusCode
    ): void {
        $location = $scooter->getLocation();

        $serializer->deserialize(
            $content,
            Location::class,
            'json',
            [
                'object_to_populate' => $location,
                'groups' => ['input']
            ]
        );

        $violations = $validator->validate($location);

        if ($violations->count() > 0) {
            // here should have been a nice loop through the violations in order to
            // make a proper list of relevant data, but in the testing case the "magic"
            // will do the trick...
            $response['violations'] = (string)$violations;
            $statusCode = HttpCode::BAD_REQUEST;
        } else {
            $this->em->persist($location);
            $this->em->flush();

            $this->em->persist($location->getScooter());
            $this->em->flush();

            if (!$location->getScooter()->getDistance()) {
                $statusCode = HttpCode::NO_CONTENT;
                $response = [];
            }
        }
    }

    /**
     * @inheritDoc
     */
    public function checkStatusCode(
        Scooter $scooter,
        int $statusCode,
        MessageBusInterface $eventBus
    ): void {
        // here we are using 204 as a trigger to set up `occupied=false` status
        // for a scooter
        if ($statusCode == HttpCode::NO_CONTENT) {
            $eventBus->dispatch(
                new ChangeScooterStatusEvent(
                    $scooter->getId(),
                    false
                )
            );
        }
    }

    /**
     * Gets scooters status
     *
     * @param array $body
     * @param string $status
     * @param $response
     */
    public function getScootersStatus(
        array $body,
        string $status,
        &$response
    ) {
        $this->checkConsistency($body['startLatitude'], $body['endLatitude']);
        $this->checkConsistency($body['endLongitude'], $body['startLongitude']);

        $response = $this->scooterRepository->findAllMatching(
            $body,
            $status
        );
    }


    /**
     * Checks consistency of latitude/longitude
     *
     * @param $startPoint
     * @param $endPoint
     */
    private function checkConsistency(&$startPoint, &$endPoint): void
    {
        if ($startPoint < $endPoint) {
            [$startPoint, $endPoint] = [$startPoint, $endPoint];
        }
    }
}
