<?php

namespace App\DataFixtures;

use App\Entity\ApiToken;
use App\Entity\Location;
use App\Entity\Scooter;
use App\Service\JWTTokenService;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * Class ScooterFixture
 *
 * @package App\DataFixtures
 */
class ScooterFixture extends BaseFixture
{
    /**
     * @var UserPasswordEncoderInterface
     */
    private $passwordEncoder;

    /**
     * @var JWTTokenService
     */
    private $JWTTokenService;

    /**
     * ScooterFixture constructor.
     *
     * @param UserPasswordEncoderInterface $passwordEncoder
     * @param JWTTokenService $JWTTokenService
     */
    public function __construct(
        UserPasswordEncoderInterface $passwordEncoder,
        JWTTokenService $JWTTokenService
    )
    {
        $this->passwordEncoder = $passwordEncoder;
        $this->JWTTokenService = $JWTTokenService;
    }

    /**
     * Creates 10 scooters
     *
     * @param ObjectManager $manager
     * @throws \Exception
     */
    protected function loadData(ObjectManager $manager)
    {
        $this->createMany(10, 'main_scooters', function($i) use ($manager) {
            $location = new Location();
            $location->setLatitude(43.6532);
            $location->setLongitude(79.3832);
            $location->setCreatedAt($this->faker->dateTimeBetween('-1 months', '-1 seconds'));
            $location->setUpdatedAt($this->faker->dateTimeBetween('-1 months', '-1 seconds'));

            $scooter = new Scooter();
            $scooter->setOccupied(0);
            $scooter->setLocation($location);

            $apiToken = new ApiToken($scooter, $this->JWTTokenService);

            $manager->persist($apiToken);
            $manager->persist($location);
            $manager->persist($scooter);

            return $scooter;
        });

        $manager->flush();
    }
}
