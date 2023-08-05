<?php

declare(strict_types=1);

namespace Domain\Shared\ValueObject;

interface ValueObject
{
    public function equals(object $object): bool;
}
