<?php

namespace App\Security\Voter;

use App\Entity\Product;
use App\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\User\UserInterface;


class ProductVoter extends Voter
{

    const PERMISSION_PRODUCT = ["POST","EDIT","REMOVE","VIEW","VIEW_ALL"];

    protected function supports($attribute, $subject)
    {


        // replace with your own logic
        // https://symfony.com/doc/current/security/voters.html
        return in_array($attribute, self::PERMISSION_PRODUCT)
            && $subject instanceof Product;
    }

    protected function voteOnAttribute($attribute, $subject, TokenInterface $token)
    {
        $user = $token->getUser();
        // if the user is anonymous, do not grant access
        if (!$user instanceof UserInterface) {
            return false;
        }

        /**
         * @var Product $seller
         */
        $seller = $subject ->getSeller();
        // ... (check conditions and return true to grant permission) ...
        switch ($attribute) {
            case 'VIEW':
                if($user === $seller){
                    return true;
                }
                break;
            case 'EDIT':
                if($user === $seller && $user->getRoles() === [User::ROLE_SELLER]){
                    return true;
                }
                break;
            case 'POST':
                if($user ->getRoles() === [User::ROLE_SELLER]){
                    return true;
                }
                break;
            case 'REMOVE':
                if($user === $seller){
                    return true;
                }
                break;
            case 'VIEW_ALL':
                if($user -> getRoles() === [User::ROLE_USER]){
                    return true;
                }

        }

        return false;
    }
}
