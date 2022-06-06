<?php

namespace App\Message\Security;

use App\Entity\User;
use App\Message\Common\MailMessage;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;

class VerifyAccountMessage extends MailMessage
{

    public function __construct(User $user)
    {
        $email = (new TemplatedEmail())
            ->to($user->getEmail())
            ->from('no-reply@symfony-blog.com')
            ->subject('Verify your email')
            ->htmlTemplate('emails/security/verify-account.html.twig')
            ->context([
                'user' => $user,
            ])
        ;

        parent::__construct($email);
    }
}