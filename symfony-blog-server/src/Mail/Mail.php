<?php

namespace App\Mail;

use Symfony\Bridge\Twig\Mime\TemplatedEmail;

abstract class Mail extends TemplatedEmail
{
    abstract public static function getTranslationKey(): string;
    abstract public function getLocale(): string;
    public function getTranslationParameters(): array
    {
        return [];
    }
}
