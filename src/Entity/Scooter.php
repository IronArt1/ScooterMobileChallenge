<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
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
     * @ORM\Column(type="boolean")
     */
    private $occupied = FALSE;

    /**
     * @ORM\OneToOne(targetEntity=Location::class, inversedBy="scooter", cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $location;

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
}
