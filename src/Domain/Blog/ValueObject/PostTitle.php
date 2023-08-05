<?php

declare(strict_types=1);

namespace Domain\Blog\ValueObject;

use Domain\Shared\Slugger\Slug;
use Domain\Shared\Slugger\SluggerInterface;
use Domain\Shared\ValueObject\StringValueObject;
use Webmozart\Assert\Assert;

final readonly class PostTitle extends StringValueObject
{
    public const MAX_LENGTH = 255;
    public const MIN_LENGTH = 8;

    protected function validate(string $value): void
    {
        Assert::lengthBetween($value, self::MIN_LENGTH, self::MAX_LENGTH);
    }

    public function toSlug(SluggerInterface $slugger): PostSlug
    {
        return new PostSlug($slugger->slug($this->__toString())->__toString());
    }
}
