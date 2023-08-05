<?php

namespace Infrastructure\Symfony\Shared\Messenger;

use Application\Shared\Query\QueryBusInterface;
use Application\Shared\Query\QueryInterface;
use Symfony\Component\Messenger\Exception\HandlerFailedException;
use Symfony\Component\Messenger\HandleTrait;
use Symfony\Component\Messenger\MessageBusInterface;

final class MessengerQueryBus implements QueryBusInterface
{
    use HandleTrait;

    public function __construct(MessageBusInterface $queryBus)
    {
        $this->messageBus = $queryBus;
    }

    public function dispatch(QueryInterface $query): mixed
    {
        try {
            return $this->handle($query);
        } catch (HandlerFailedException $e) {
            /**
             * @var non-empty-list<\Throwable> $exceptions
             */
            $exceptions = $e->getNestedExceptions();

            throw next($exceptions);
        }
    }
}
