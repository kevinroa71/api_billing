<?php

namespace App\EventSubscriber;

use App\Entity\User;
use App\Entity\Billing;
use Symfony\Component\Mime\Email;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\HttpKernel\Event\ViewEvent;
use ApiPlatform\Core\EventListener\EventPriorities;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

final class BillingSubscriber implements EventSubscriberInterface
{
    protected $security;
    protected $mailer;
    protected $container;

    /**
     * Construction function
     *
     * @param Security           $security  Service Security
     * @param MailerInterface    $mailer    Service Mailer
     * @param ContainerInterface $container Container Services
     */
    public function __construct(
        Security $security,
        MailerInterface $mailer,
        ContainerInterface $container
    ) {
        $this->security = $security;
        $this->mailer = $mailer;
        $this->container = $container;
    }

    /**
     * Events Subscriber
     *
     * @return void
     */
    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::VIEW => [
                ['setUser', EventPriorities::PRE_WRITE],
                ['sendMail', EventPriorities::POST_WRITE],
            ],
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

    /**
     * Send email with the payment link
     *
     * @param ViewEvent $event Event
     *
     * @return void
     */
    public function sendMail(ViewEvent $event): void
    {
        $billing = $event->getControllerResult();
        $method = $event->getRequest()->getMethod();

        if (!$billing instanceof Billing || Request::METHOD_POST !== $method) {
            return;
        }

        $sender_email = $this->container->getParameter("mailer.sender_email");

        $email = (new TemplatedEmail())
            ->from($sender_email)
            ->to($billing->getEmail())
            ->priority(Email::PRIORITY_HIGH)
            ->subject('New Payment Link!')
            ->htmlTemplate('emails/billing.html.twig')
            ->context(['billing' => $billing]);

        $this->mailer->send($email);
    }
}
