<?php

namespace Infrastructure\Symfony\Shared\Messenger;

use Application\Shared\Command\CommandBusInterface;
use Application\Shared\Command\CommandInterface;
use Symfony\Component\Messenger\Exception\HandlerFailedException;
use Symfony\Component\Messenger\HandleTrait;
use Symfony\Component\Messenger\MessageBusInterface;

final class MessengerCommandBus implements CommandBusInterface
{
    use HandleTrait;

    public function __construct(MessageBusInterface $commandBus)
    {
        $this->messageBus = $commandBus;
    }

    public function dispatch(CommandInterface $command): mixed
    {
        try {
            return $this->handle($command);
        } catch (HandlerFailedException $e) {
            /**
             * @var non-empty-list<\Throwable> $exceptions
             */
            $exceptions = $e->getNestedExceptions();

            throw next($exceptions);
        }
    }
}
