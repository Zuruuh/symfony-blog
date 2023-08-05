<?php

declare(strict_types=1);

namespace Domain\Blog\Model;

use Domain\Blog\ValueObject\PostContent;
use Domain\Blog\ValueObject\PostId;
use Domain\Blog\ValueObject\PostSlug;
use Domain\Blog\ValueObject\PostTitle;

final class Post
{
    public function __construct(
        public readonly PostId $id,
        private PostTitle $title,
        private PostContent $content,
        private PostSlug $slug,
    ) {}

    public function getTitle(): PostTitle
    {
        return $this->title;
    }

    public function getContent(): PostContent
    {
        return $this->content;
    }

    public function getSlug(): PostSlug
    {
        return $this->slug;
    }
}
