<?php

namespace App\EventSubscriber;

use App\Entity\User;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\HttpKernel\Event\ViewEvent;
use ApiPlatform\Core\EventListener\EventPriorities;
use App\Entity\Billing;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

final class BillingSubscriber implements EventSubscriberInterface
{
    protected $security;

    /**
     * Construction function
     *
     * @param Security $security Service Security
     */
    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    /**
     * Events Subscriber
     *
     * @return void
     */
    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::VIEW => ['setUser', EventPriorities::PRE_WRITE],
        ];
    }

    /**
     * Assign the user to billing
     *
     * @param ViewEvent $event Event
     *
     * @return void
     */
    public function setUser(ViewEvent $event): void
    {
        $billing = $event->getControllerResult();
        $method = $event->getRequest()->getMethod();
        $user = $this->security->getUser();

        if (!$billing instanceof Billing
            || !$user instanceof User
            || Request::METHOD_POST !== $method
        ) {
            return;
        }

        if (!$billing->getUser()) {
            $billing->setUser($user);
        }
    }
}
