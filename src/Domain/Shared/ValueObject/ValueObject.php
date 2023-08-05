<?php

declare(strict_types=1);

namespace Domain\Shared\ValueObject;

/**
 * @template T of object
 */
interface ValueObject
{
    /**
     * @param T $object
     */
    public function equals(object $object): bool;
}
