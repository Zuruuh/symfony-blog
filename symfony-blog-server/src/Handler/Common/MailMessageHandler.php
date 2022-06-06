<?php

namespace App\Handler\Common;

use App\Common\Message\AsyncMessageInterface;
use App\Mail\Mail;
use App\Message\Common\MailMessage;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;
use Symfony\Component\Messenger\Handler\MessageSubscriberInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

#[AsMessageHandler(fromTransport: 'async')]
class MailMessageHandler implements MessageHandlerInterface, MessageSubscriberInterface
{
    public function __construct(
        private readonly MailerInterface $mailer,
        private readonly TranslatorInterface $translator,
    ) {}

    /**
     * @throws TransportExceptionInterface
     */
    public function __invoke(MailMessage $mailMessage)
    {
        // TODO: Fix this being sent synchronously when is should be async
        $mail = $this->translateEmail($mailMessage->getEmail());

        $this->mailer->send($mail);
    }

    private function translateEmail(Mail $mail): Mail
    {
        $subject = $mail::getTranslationKey() . '.title';
        $mail->subject($this->translator->trans($subject, $mail->getTranslationParameters(), 'emails', $mail->getLocale()));

        return $mail;
    }

    public static function getHandledMessages(): iterable
    {
        yield AsyncMessageInterface::class;
    }
}