<?php

namespace App\Eventsubscribers;

use ApiPlatform\Core\EventListener\EventPriorities;
use App\Entity\Product;
use App\Entity\User;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ViewEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class SetSellerSubscriber implements EventSubscriberInterface
{

    /**
     * @var TokenStorageInterface
     */
    private $tokenStorage;

    public function __construct(TokenStorageInterface $tokenStorage)
    {
        $this->tokenStorage = $tokenStorage;   
    }

    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::VIEW => ['setSeller',EventPriorities::PRE_WRITE]
        ];
    }

    public function setSeller(ViewEvent $event)
    {
        $method = $event -> getRequest() -> getMethod();
        $entity = $event -> getControllerResult();

        $token = $this->tokenStorage->getToken();

        if($token === null){
            return;
        }

        if(!$entity instanceof Product || $method !== Request::METHOD_POST){
            return false;
        }

        /** @var User */
        $user = $token ->getUser();

        $entity->setSeller($user);


    }
    
}