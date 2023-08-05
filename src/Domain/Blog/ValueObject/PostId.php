<?php

declare(strict_types=1);

namespace Domain\Blog\ValueObject;

use Domain\Shared\ValueObject\AggregateRootId;

final readonly class PostId
{
    use AggregateRootId;
}
