<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
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
     * @ORM\OneToMany(targetEntity="App\Entity\ApiToken", mappedBy="scooter", orphanRemoval=true)
     */
    protected $apiTokens;
}
