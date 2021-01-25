<?php

namespace App\DataFixtures;

use App\Entity\ApiToken;
use App\Entity\Mobile;
use App\Service\JWTTokenService;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class MobileFixture extends BaseFixture
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
        $this->createMany(3, 'main_mobiles', function($i) use ($manager) {
            $mobile = new Mobile();

            $apiToken = new ApiToken($mobile, $this->JWTTokenService);
            $manager->persist($apiToken);

            return $mobile;
        });

        $manager->flush();
    }
}
