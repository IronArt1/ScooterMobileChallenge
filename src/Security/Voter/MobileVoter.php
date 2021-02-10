<?php

namespace App\Security\Voter;

use App\Entity\Mobile;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;

class MobileVoter extends Voter
{
    private $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    protected function supports($attribute, $subject)
    {
        return in_array($attribute, ['OBSERVE'])
            && $subject instanceof Mobile;
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
        /** @var Mobile $subject */
        $mobile = $token->getUser();

        switch ($attribute) {
            case 'OBSERVE':
                if ($subject === $mobile) {
                    return true;
                }

                if ($this->security->isGranted('MOBILE_ADMIN_ROLE)')) {
                    return true;
                }

                return false;
        }

        return false;
    }
}
