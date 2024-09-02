<?php

namespace App\Security\Voter;

use App\Entity\Advertisement;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\User\UserInterface;

class AdvertisementVoter extends Voter
{
    public const EDIT_DELETE_ADVERTISEMENT = 'EDIT_DELETE_ADVERTISEMENT';

    protected function supports(string $attribute, mixed $subject): bool
    {
        return self::EDIT_DELETE_ADVERTISEMENT === $attribute
            && $subject instanceof Advertisement;
    }

    protected function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token): bool
    {
        $user = $token->getUser();

        if (!$user instanceof UserInterface) {
            return false;
        }


        switch ($attribute) {
            case self::EDIT_DELETE_ADVERTISEMENT:
                if ($user === $subject->getOwner()) {
                    return true;
                }
                break;
            default:
                return false;
        }

        return false;
    }
}
