<?php

namespace App\Eventsubscribers;

use ApiPlatform\Core\EventListener\EventPriorities;
use App\Entity\Product;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\ViewEvent;
use Symfony\Component\HttpKernel\KernelEvents;

class HashStoringDateSubscriber implements EventSubscriberInterface
{
    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::VIEW => ['setDate', EventPriorities::PRE_WRITE]
        ];
    }

    public function setDate(ViewEvent $event)
    {
        $entity = $event -> getControllerResult();
        $method = $event -> getRequest() -> getMethod();

        if(!$entity instanceof Product || !in_array($method , [Request::METHOD_POST , Request::METHOD_PUT])){
            return;
        }

        $entity->setProductDate(new \DateTime());
    }
}