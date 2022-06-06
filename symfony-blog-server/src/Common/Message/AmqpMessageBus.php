<?php

namespace App\Common\Message;

use App\Handler\Common\MailMessageHandler;
use App\Message\Common\MailMessage;
use InvalidArgumentException;
use Symfony\Component\Messenger\Bridge\Amqp\Transport\AmqpStamp;
use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\Handler\HandlersLocator;
use Symfony\Component\Messenger\MessageBus;
use Symfony\Component\Messenger\Middleware\HandleMessageMiddleware;

class AmqpMessageBus extends MessageBus
{
    public function __construct(MailMessageHandler $handler)
    {
        parent::__construct([
            new HandleMessageMiddleware(new HandlersLocator([
                MailMessage::class => [$handler]
            ]))
        ]);
    }

    /**
     * @override
     * {@inheritdoc}
     *
     * @param AsyncMessageInterface $message
     *
     * @throws InvalidArgumentException
     */
    public function dispatch(object $message, array $stamps = []): Envelope
    {
        if (!$message instanceof AsyncMessageInterface) {
            $message = sprintf('Passed invalid message to Amqp message bus. Expected class to be "%s", found "%s" instead!', AsyncMessageInterface::class, $message::class);

            throw new InvalidArgumentException($message);
        }

//        $stamps = empty($stamps) ? [new AmqpStamp('mail', AMQP_NOPARAM, [])] : $stamps;

        return parent::dispatch($message, $stamps);
    }
}