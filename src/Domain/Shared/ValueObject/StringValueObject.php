<?php

declare(strict_types=1);

namespace Domain\Shared\ValueObject;

use Stringable;

/**
 * @implements ValueObject<static>
 */
abstract readonly class StringValueObject implements ValueObject, Stringable
{
    public function __construct(private string $value)
    {
        $this->validate($value);
    }

    protected function validate(string $value): void {}

    public function equals(object $other): bool
    {
        return $other instanceof static && $other->__toString() === $this->__toString();
    }

    public function __toString(): string
    {
        return $this->value;
    }
}
