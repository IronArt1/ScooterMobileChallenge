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
    const LOCATION_SETS = [
        // King St W
        [
            // Latitude's (start's)
            43.645319,
            // Longitude's (start's)
            -79.395769,
            // Longitude's (end's)
            -79.438640
        ],
        // Queen Street W
        // 79.445822-79.404049=0.041773(4.11km)
        [43.647183, -79.404049, -79.445822],
        // Dundas St W
        [43.652427, -79.405568, -79.420914],
        // 713 College St
        [43.655174, -79.419079, -79.440305],
        // 4-32 Argyle St
        [43.647395, -79.417552, -79.428136],

        // Niagara St
        [
            // Latitude's (start's)
            43.657552,
            // Longitude's (start's)
            -79.415174,
            // Latitude's (end's)
            43.641719
        ],
        // West Queen West
        [43.645193, -79.413166, 43.634827],
        // Liberty Village
        [43.657637, -79.425798, 43.634770],
        // Little Portugal
        [43.657666, -79.421745, 43.641662],
        // Aqua Condo
        [43.658890, -79.392154, 43.637874]
    ];

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
            $location->setLatitude(self::LOCATION_SETS[$i][0]);
            $location->setLongitude(self::LOCATION_SETS[$i][1]);
            $location->setDestination(self::LOCATION_SETS[$i][2]);
            $location->setCreatedAt($this->faker->dateTimeBetween('-1 months', '-1 seconds'));
            $location->setUpdatedAt($this->faker->dateTimeBetween('-1 months', '-1 seconds'));

            $scooter = new Scooter();
            $scooter->setOccupied(0);
            $scooter->setLocation($location);
            // 0b01|0b10 - the first/second bits are directions on a street
            // 0b1000|0b0100 - the third/forth bits make a differentiation what we have in
            //       `Location->Destination` which are: Latitude/Longitude accordingly
            $scooter->setMetadata(($i < 5) ? 0b0101 : 0b1001);
            $scooter->setDistance(0);

            $apiToken = new ApiToken($scooter, $this->JWTTokenService);

            $manager->persist($apiToken);
            $manager->persist($location);
            $manager->persist($scooter);

            return $scooter;
        });

        $manager->flush();
    }
}
