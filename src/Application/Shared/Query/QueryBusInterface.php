<?php

declare(strict_types=1);

namespace Application\Shared\Query;

interface QueryBusInterface
{
    public function dispatch(QueryInterface $command): mixed;
}
