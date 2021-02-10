<?php

namespace App\Entity;

use Faker\Factory;
use App\Service\JWTTokenService;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

/**
 * A very simple representation of JWT is that is actually useless in a real world...
 * In reality we have to use AccessToken|RefreshToken an example of those I put into:
 *    - App\Entity\oAuth\...
 *
 * @ORM\Entity(repositoryClass="App\Repository\ApiTokenRepository")
 */
class ApiToken
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @Groups("main")
     * @ORM\Column(type="string", length=512)
     */
    private $token;

    /**
     * @ORM\Column(type="string", length=20)
     */
    private $type;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Mobile", inversedBy="apiTokens")
     * @ORM\JoinColumn(nullable=true)
     */
    private $mobile;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Scooter", inversedBy="apiTokens")
     * @ORM\JoinColumn(nullable=true)
     */
    private $scooter;

    /**
     * ApiToken constructor.
     *
     * @param Mobile|Scooter $owner
     * @param JWTTokenService $JWTTokenService
     * @throws \Exception
     */
    public function __construct(
        BaseEntity $owner,
        JWTTokenService $JWTTokenService
    ) {
        $faker = Factory::create();

        // here we can actually see why it's better to carry out any parameters in JWT
        $this->token = $JWTTokenService->generateToken(
            [
                'fakeParam1' => $faker->uuid,
                'fakeParam2' => $faker->bankAccountNumber,
                'fakeParam3' => $faker->company,
                'fakeParam4' => $faker->currencyCode
            ]
        );
        $this->type = $owner::OWNER_TYPE;
        $this->{$this->type} = $owner;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getToken(): ?string
    {
        return $this->token;
    }

    public function getOwner(): BaseEntity
    {
        return $this->{$this->type};
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function getScooter(): Scooter
    {
        return $this->scooter;
    }

    /**
     * Is used with ValidatorInterface
     *
     * @Assert\Callback
     */
    public function validate(ExecutionContextInterface $context, $payload)
    {
        if (is_null($this->mobile) && is_null($this->scooter)) {
            $context->buildViolation('Either mobile_id or scooter_id must be set up!')
                ->atPath('mobile')
                ->atPath('scooter')
                ->addViolation();
        }
    }

}
