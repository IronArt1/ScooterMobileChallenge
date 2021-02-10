<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * Class Mobile
 *
 * @ORM\Entity(repositoryClass="App\Repository\MobileRepository")
 * @package App\Entity
 */
class Mobile extends BaseEntity implements UserInterface
{
    /**
     * @Groups("main")
     * @ORM\OneToMany(targetEntity="App\Entity\ApiToken", mappedBy="mobile", orphanRemoval=true)
     */
    protected $apiTokens;

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        // right now just faking it
        // and yes I know that it is a violation of SOLID
        // -> Interface segregation principle
        return ['OBSERVE'];
    }
}
