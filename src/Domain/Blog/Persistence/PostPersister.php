<?php

declare(strict_types=1);

namespace Domain\Blog\Persistence;

use Domain\Blog\Model\Post;

interface PostPersister
{
    public function persist(Post $post): void;
}
