<?php

declare(strict_types=1);

namespace Domain\Blog\Model;

use Domain\Blog\ValueObject\PostContent;
use Domain\Blog\ValueObject\PostId;
use Domain\Blog\ValueObject\PostTitle;

final class Post
{
    public function __construct(
        public readonly PostId $id,
        private PostTitle $title,
        private PostContent $content,
    ) {}

    public function getTitle(): PostTitle
    {
        return $this->title;
    }

    public function getContent(): PostContent
    {
        return $this->content;
    }
}
