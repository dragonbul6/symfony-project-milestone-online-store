<?php

namespace App\Security\Voter;

use App\Entity\Cart;
use App\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\User\UserInterface;

class CartVoter extends Voter
{

    protected function supports($attribute, $subject)
    {
        // replace with your own logic
        // https://symfony.com/doc/current/security/voters.html
        return in_array($attribute, ['VIEW', 'REMOVE','CHECKOUT'])
            && $subject instanceof \App\Entity\Cart;
    }

    protected function voteOnAttribute($attribute, $subject, TokenInterface $token)
    {
        $user = $token->getUser();

        /** @var Cart */
        $owner = $subject->getBuyer();

        // if the user is anonymous, do not grant access
        if (!$user instanceof UserInterface) {
            return false;
        }

        // ... (check conditions and return true to grant permission) ...
        switch ($attribute) {
            case 'VIEW':
                if($owner === $user){
                    return true;
                }
                break;
            case 'REMOVE': 
                if($owner === $user){
                    return true;
                }
                break;
            case 'CHECKOUT':
                if($owner === $user){
                    return true;
                }
                break;
        }

        return false;
    }

    // public static function checkCartOwner(User $user,Cart $cartobj): bool
    // {
    //     //this function will check whether the cart belong to user or not?. 

    //     $carts = $user->getCarts();
    //    if($carts->contains($cartobj)){
    //         return true;
    //    }
        
    //     return false;
    // }
}
