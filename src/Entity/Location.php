<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\LocationRepository;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Serializer\Annotation\Groups;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

/**
 * @ORM\Entity(repositoryClass=LocationRepository::class)
 * @ORM\EntityListeners({"App\EventListener\LocationSetListener"})
 */
class Location
{
    use TimestampableEntity;

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @Groups({"input", "main"})
     * @ORM\Column(type="decimal", precision=20, scale=16)
     * @Assert\NotNull(message="Please set a latitude")
     * @Assert\Regex("/\d+(\.\d+)?/")
     */
    private $latitude;

    /**
     * @Groups({"input", "main"})
     * @ORM\Column(type="decimal", precision=20, scale=16)
     * @Assert\NotNull(message="Please set a longitude")
     * @Assert\Regex("/\d+(\.\d+)?/")
     */
    private $longitude;

    /**
     * @ORM\Column(type="decimal", precision=20, scale=16)
     * @Assert\Regex("/\d+(\.\d+)?/")
     */
    private $destination;

    /**
     * @var \DateTime
     * @Groups({"input"})
     * @Gedmo\Timestampable(on="update")
     * @ORM\Column(type="datetime")
     * @Assert\Callback({"App\Entity\Location", "validate"})
     */
    protected $updatedAt;

    /**
     * @ORM\OneToOne(targetEntity=Scooter::class, mappedBy="location", cascade={"persist", "remove"})
     */
    private $scooter;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLatitude(): ?string
    {
        return $this->latitude;
    }

    public function setLatitude(string $latitude): self
    {
        $this->latitude = (double)$latitude;

        return $this;
    }

    public function getLongitude(): ?string
    {
        return $this->longitude;
    }

    /**
     * Adjusts a coordinate
     *
     * @param $factor
     * @param string $parameter
     * @return string|null
     */
    public function getAdjustedValue($factor, string $parameter, $meta): ?string
    {
        $this->$parameter += $factor;

        if ($meta & 0b0001) {
            if ($meta & 0b0100) {
                if (abs($this->$parameter) >= abs($this->destination)) {
                    $this->$parameter = $this->destination;
                }
            } else {
                if (abs($this->$parameter) <= abs($this->destination)) {
                    $this->$parameter = $this->destination;
                }
            }
        } else {
            if ($meta & 0b0100) {
                if (abs($this->$parameter) <= abs($this->destination)) {
                    $this->$parameter = $this->destination;
                }
            } else {
                if (abs($this->$parameter) >= abs($this->destination)) {
                    $this->$parameter = $this->destination;
                }
            }
        }

        return (string)$this->$parameter;
    }

    public function setLongitude(string $longitude): self
    {
        $this->longitude = (double)$longitude;

        return $this;
    }

    public function getScooter(): ?Scooter
    {
        return $this->scooter;
    }

    public function setScooter(Scooter $scooter): self
    {
        // set the owning side of the relation if necessary
        if ($scooter->getLocation() !== $this) {
            $scooter->setLocation($this);
        }

        $this->scooter = $scooter;

        return $this;
    }

    /**
     * An example of a custom validator's,  instead of using Assert\DateTime
     * Although I do like more working with "class ... extends ConstraintValidator"
     *
     * @param \DateTime $context
     * @param ExecutionContextInterface $context
     * @param $payload
     */
    public function validate(\DateTime $content, ExecutionContextInterface $context, $payload)
    {
        if (!$content instanceof \DateTime) {
            $context->buildViolation('A value of updateAt must be DateTime type.')
                ->atPath('updatedAt')
                ->addViolation();
        }
    }

    public function getDestination(): ?string
    {
        return $this->destination;
    }

    public function setDestination(string $destination): self
    {
        $this->destination = $destination;

        return $this;
    }
}
