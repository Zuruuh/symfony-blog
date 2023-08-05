<?php

declare(strict_types=1);

namespace Application\Blog\Command;

use Domain\Blog\ValueObject\PostContent;
use Domain\Blog\ValueObject\PostTitle;

final readonly class CreateBlogPostCommand
{
    public function __construct(
        public PostTitle $title,
        public PostContent $content,
    ) {}
}
