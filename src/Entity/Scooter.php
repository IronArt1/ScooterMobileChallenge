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
     * @ORM\Column(type="decimal", precision=20, scale=16, nullable=true)
     */
    private $latitude;

    /**
     * @ORM\Column(type="decimal", precision=20, scale=16, nullable=true)
     */
    private $longitude;

    /**
     * @ORM\Column(type="boolean")
     */
    private $occupied = FALSE;

    public function getLatitude(): ?string
    {
        return $this->latitude;
    }

    public function setLatitude(?string $latitude): self
    {
        $this->latitude = $latitude;

        return $this;
    }

    public function getLongitude(): ?string
    {
        return $this->longitude;
    }

    public function setLongitude(?string $longitude): self
    {
        $this->longitude = $longitude;

        return $this;
    }

    public function getOccupied(): ?bool
    {
        return $this->occupied;
    }

    public function setOccupied(bool $occupied): self
    {
        $this->occupied = $occupied;

        return $this;
    }
}
