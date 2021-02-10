<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\{
    Collection,
    ArrayCollection
};
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Bridge\Doctrine\IdGenerator\UuidV4Generator;

/**
 * Class BaseEntity
 * @package App\Entity
 */
class BaseEntity
{
    /**
     * An owner type is
     */
    const OWNER_TYPE = 'mobile';

    /**
     * A none type holder's
     */
    const NONE_HOLDER = 'NONE';

    /**
     * A null type holder's
     */
    const NULL_HOLDER = 'null';

    /**
     * @ORM\Id()
     * @ORM\Column(type="uuid", unique=true)
     * @ORM\GeneratedValue(strategy="CUSTOM")
     * @ORM\CustomIdGenerator(class=UuidV4Generator::class)
     * @Groups("main")
     */
    protected $id;

    /**
     * @var array
     */
    protected $globalStack = [];

    /**
     * @param $arg
     * @param $value
     */
    public function __set($arg, $value)
    {
        if (property_exists($this, $arg)) {
            $this->{$arg} = $value;
        } else {
            $this->globalStack[$arg] = $value;
        }
    }

    /**
     * @param $arg
     * @return mixed|null
     */
    public function __get($arg)
    {
        if (property_exists($this, $arg) && isset($this->{$arg})) {
            $value = $this->{$arg};
        } elseif (isset($this->globalStack[$arg])) {
            $value = $this->getGlobalStackValue($arg);
        } else {
            $value = null;
        }

        return $value;
    }

    public function __construct()
    {
        $this->apiTokens = new ArrayCollection();
    }

    public function getID(): ?string
    {
        return $this->id;
    }

    /**
     * @return Collection|ApiToken[]
     */
    public function getApiTokens(): Collection
    {
        return $this->apiTokens;
    }

    public function addApiToken(ApiToken $apiToken): self
    {
        if (!$this->apiTokens->contains($apiToken)) {
            $this->apiTokens[] = $apiToken;
            $apiToken->{'set' . ucfirst(static::OWNER_TYPE)}($this);
        }

        return $this;
    }

    public function removeApiToken(ApiToken $apiToken): self
    {
        if ($this->apiTokens->contains($apiToken)) {
            $this->apiTokens->removeElement($apiToken);
            // set the owning side to null (unless already changed)
            if ($apiToken->getOwner() === $this) {
                $apiToken->{'set' . ucfirst(static::OWNER_TYPE)}(null);
            }
        }

        return $this;
    }

    /**
     * @return string
     * @throws \ReflectionException
     */
    public function __toString()
    {
        $reflectionInstance = new \ReflectionClass($this);

        return (($reflectionInstance)->isAnonymous()) ?
            $reflectionInstance->getParentClass()->getShortName() :
            $reflectionInstance->getShortName();
    }

    public function getGlobalStackValue($arg)
    {
        return $this->globalStack[$arg];
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        // right now just faking it
        // and yes I know that it is a violation of SOLID
        // -> Interface segregation principle
        return ['MANAGE'];
    }

    public function getPassword()
    {
        // right now just faking it
        // and yes I know that it is a violation of SOLID
        // -> Interface segregation principle
        return '';
    }

    public function getSalt()
    {
        // right now just faking it
        // and yes I know that it is a violation of SOLID
        // -> Interface segregation principle
        return '';
    }

    public function getUsername()
    {
        // right now just faking it
        // and yes I know that it is a violation of SOLID
        // -> Interface segregation principle// right now just faking it
        return '';
    }

    public function eraseCredentials()
    {
        // right now just faking it
        // and yes I know that it is a violation of SOLID
        // -> Interface segregation principle
    }
}
