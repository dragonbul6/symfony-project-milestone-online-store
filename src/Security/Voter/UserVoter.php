<?php

namespace App\Security\Voter;

use App\Entity\User;

use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\User\UserInterface;

class UserVoter extends Voter
{
    protected function supports($attribute, $subject)
    {
        // replace with your own logic
        // https://symfony.com/doc/current/security/voters.html

        return in_array($attribute, ['VIEW', 'WRITE','VIEW_ALL'])
            && $subject instanceof User;
    }

    protected function voteOnAttribute($attribute, $subject, TokenInterface $token)
    {
        $user = $token->getUser();
        // if the user is anonymous, do not grant access
        if (!$user instanceof UserInterface) {
            return false;
        }

        /**
         * @var User $user_entity
         */
        $user_entity = $subject;

        // ... (check conditions and return true to grant permission) ...
        switch ($attribute) {
            case 'VIEW':
                if($user_entity === $user || $user->getRoles() === [User::ROLE_ADMIN]){
                    return true;
                }
                break;
            case 'WRITE':
                if($user_entity === $user){
                    return true;
                }
                break;
        }

        return false;
    }
}
