<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * Class Scooter
 *
 * @ORM\Entity(repositoryClass="App\Repository\ScooterRepository")
 * @package App\Entity
 */
class Scooter extends BaseEntity implements UserInterface
{
    /**
     * An owner type is
     */
    const OWNER_TYPE = 'scooter';

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\ApiToken", mappedBy="scooter", orphanRemoval=true)
     */
    protected $apiTokens;

    /**
     * @Groups("main")
     * @ORM\Column(type="boolean")
     */
    private $occupied = FALSE;

    /**
     * @Groups("main")
     * @ORM\OneToOne(targetEntity=Location::class, inversedBy="scooter", cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $location;

    /**
     * @ORM\Column(type="binary")
     */
    private $metadata;

    /**
     * @ORM\Column(type="decimal", precision=20, scale=16)
     */
    private $distance;

    public function getOccupied(): ?bool
    {
        return $this->occupied;
    }

    public function setOccupied(bool $occupied): self
    {
        $this->occupied = $occupied;

        return $this;
    }

    public function getLocation(): ?Location
    {
        return $this->location;
    }

    public function setLocation(Location $location): self
    {
        $this->location = $location;

        return $this;
    }

    public function getMetadata()
    {
        return $this->metadata;
    }

    public function setMetadata($metadata): self
    {
        $this->metadata = $metadata;

        return $this;
    }

    public function getDistance(): ?string
    {
        return $this->distance;
    }

    public function setDistance(string $distance): self
    {
        $this->distance = $distance;

        return $this;
    }
}
