<?php

namespace App\Mail\Security;

use App\Entity\User;
use App\Mail\Mail;
use Symfony\Component\Mime\Address;

class ValidateAccountMail extends Mail
{
    public function __construct(private readonly string $locale, User $user, string $link)
    {
        parent::__construct();

        $this
            ->to(new Address($user->getEmail(), $user->getUsername()))
            ->from('no-reply@symfony-blog.io')
            ->htmlTemplate('emails/security/verify-account.html.twig')
            ->context([
                'username' => $user->getUsername(),
                'link' => $link,
                'locale' => $locale,
            ])
        ;

    }

    public function getLocale(): string
    {
        return $this->locale;
    }

    public static function getTranslationKey(): string
    {
        return 'security.verify_account';
    }
}