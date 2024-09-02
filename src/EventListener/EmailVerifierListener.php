<?php

namespace App\EventListener;

use App\Event\UserConfirmationEmailNotReceived;
use App\Event\UserRegistered;
use App\Security\EmailVerifier;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class EmailVerifierListener implements EventSubscriberInterface
{
    private EmailVerifier $emailVerifier;

    public function __construct(EmailVerifier $emailVerifier)
    {
        $this->emailVerifier = $emailVerifier;
    }

    public static function getSubscribedEvents()
    {
        return [
            UserRegistered::NAME => 'onUserRegistered',
            UserConfirmationEmailNotReceived::NAME => 'onUserConfirmationEmailNotReceived',
        ];
    }

    public function onUserRegistered(UserRegistered $event)
    {
        $user = $event->getUser();
        $this->sendConfirmationEmail($user);
    }

    public function onUserConfirmationEmailNotReceived(UserConfirmationEmailNotReceived $event)
    {
        $user = $event->getUser();
        $this->sendConfirmationEmail($user);
    }

    private function sendConfirmationEmail($user)
    {
        $this->emailVerifier->sendEmailConfirmation(
            'app_verify_email',
            $user,
            (new TemplatedEmail())
                ->to($user->getEmail())
                ->subject('Please Confirm your Email')
                ->htmlTemplate('registration/confirmation_email.html.twig')
        );
    }
}
