<?php

declare(strict_types=1);

namespace Application\Blog\Command;

use Application\Shared\Command\CommandInterface;
use Domain\Blog\ValueObject\PostContent;
use Domain\Blog\ValueObject\PostTitle;

final readonly class CreateBlogPostCommand implements CommandInterface
{
    public function __construct(
        public PostTitle $title,
        public PostContent $content,
    ) {}
}
