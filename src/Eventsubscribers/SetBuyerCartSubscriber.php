<?php

namespace App\Eventsubscribers;

use ApiPlatform\Core\EventListener\EventPriorities;
use App\Entity\Cart;
use App\Entity\User;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\ViewEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class SetBuyerCartSubscriber implements EventSubscriberInterface
{

    /**
     * @var TokenStorageInterface
     */
    private $tokenStorage;

    public function __construct(TokenStorageInterface $token)
    {
        $this->tokenStorage = $token;
    }

    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::VIEW => ['setBuyer',EventPriorities::PRE_WRITE]
        ];
    }

    public function setBuyer(ViewEvent $event)
    {
        $method = $event->getRequest() ->getMethod();
        $entity = $event->getControllerResult();
        $token = $this->tokenStorage->getToken();

        if(!$entity instanceof Cart || $method !== Request::METHOD_POST){
            return;
        }

        /**
         * @var User
         */
        $user = $token->getUser();

       
        $entity->setBuyer($user);
        $entity->setAddedDate(new \DateTime());

    }
}