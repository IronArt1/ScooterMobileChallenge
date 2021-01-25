<?php

namespace App\DataFixtures;

use App\Entity\ApiToken;
use App\Entity\Scooter;
use App\Service\JWTTokenService;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class ScooterFixture extends BaseFixture
{
    private $passwordEncoder;

    private $JWTTokenService;

    public function __construct(
        UserPasswordEncoderInterface $passwordEncoder,
        JWTTokenService $JWTTokenService
    )
    {
        $this->passwordEncoder = $passwordEncoder;
        $this->JWTTokenService = $JWTTokenService;
    }

    protected function loadData(ObjectManager $manager)
    {
        $this->createMany(10, 'main_scooters', function($i) use ($manager) {
            $scooter = new Scooter();

            $apiToken = new ApiToken($scooter, $this->JWTTokenService);
            $manager->persist($apiToken);

            return $scooter;
        });

        $manager->flush();
    }
}
