<?php

namespace App\EventSubscriber;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\HttpKernel\Event\ViewEvent;
use ApiPlatform\Core\EventListener\EventPriorities;
use App\Entity\User;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

final class UserSubscriber implements EventSubscriberInterface
{
    protected $passwordEncoder;

    /**
     * Construction function
     *
     * @param UserPasswordEncoderInterface $passwordEncoder Service Encoder
     */
    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }

    /**
     * Events Subscriber
     *
     * @return void
     */
    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::VIEW => ['passwordEncoder', EventPriorities::PRE_WRITE],
        ];
    }

    /**
     * User password encoder
     *
     * @param ViewEvent $event Event
     *
     * @return void
     */
    public function passwordEncoder(ViewEvent $event): void
    {
        $user = $event->getControllerResult();
        $method = $event->getRequest()->getMethod();

        if (!$user instanceof User || Request::METHOD_POST !== $method) {
            return;
        }

        $password = $this->passwordEncoder->encodePassword(
            $user,
            $user->getPassword()
        );

        $user->setPassword($password);
    }
}
