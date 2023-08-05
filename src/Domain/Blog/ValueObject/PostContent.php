<?php

declare(strict_types=1);

namespace Domain\Blog\ValueObject;

use Domain\Shared\ValueObject\StringValueObject;
use Webmozart\Assert\Assert;

final readonly class PostContent extends StringValueObject
{
    public const MIN_LENGTH = 32;
    public const MAX_LENGTH = 16384;

    protected function validate(string $value): void
    {
        Assert::lengthBetween($value, self::MIN_LENGTH, self::MAX_LENGTH);
    }
}
