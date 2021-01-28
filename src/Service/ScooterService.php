<?php

namespace App\Service;

use App\Entity\{
    Scooter,
    Location
};
use Codeception\Util\HttpCode;
use App\Repository\ScooterRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Interfaces\Service\ScooterServiceInterface;
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
        }
    }
}
