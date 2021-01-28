<?php

namespace App\Security\Voter;

use App\Entity\Scooter;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\Security;

class ScooterVoter extends Voter
{
    private $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    protected function supports($attribute, $subject)
    {
        return in_array($attribute, ['MANAGE'])
            && $subject instanceof Scooter;
    }

    /**
     * Here we can verify that a scooter is an actual owner of its table record
     * and can make requested changes (against imposters)
     *
     * @param string $attribute
     * @param mixed $subject
     * @param TokenInterface $token
     * @return bool
     */
    protected function voteOnAttribute($attribute, $subject, TokenInterface $token)
    {
        /** @var Scooter $subject */
        $scooter = $token->getUser();

        switch ($attribute) {
            case 'MANAGE':
                if ($subject === $scooter) {
                    return true;
                }

                if ($this->security->isGranted('ROLE_ADMIN_SCOOTER')) {
                    return true;
                }

                return false;
        }

        return false;
    }
}
