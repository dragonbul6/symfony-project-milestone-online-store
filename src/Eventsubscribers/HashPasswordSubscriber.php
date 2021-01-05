<?php

namespace App\Eventsubscribers;

use ApiPlatform\Core\EventListener\EventPriorities;
use App\Entity\User;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\ViewEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class HashPasswordSubscriber implements EventSubscriberInterface
{
    /**
     * @var UserPasswordEncoderInterface
     */
    private $passwordEncoder;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }


    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::VIEW => ['hashingPassword',EventPriorities::PRE_WRITE]
        ];
    }

    public function hashingPassword(ViewEvent $event)
    {
        $method = $event->getRequest()->getMethod();
        
        $user = $event->getControllerResult();

        if(!$user instanceof User ||  !in_array($method,[Request::METHOD_POST,Request::METHOD_PUT]))
        {
            return;
        }

        $user->setPassword(
            $this->passwordEncoder->encodePassword($user, $user->getPassword())
        );
    }
}