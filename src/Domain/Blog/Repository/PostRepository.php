<?php

declare(strict_types=1);

namespace Domain\Blog\Repository;

use Domain\Blog\Model\Post;
use Domain\Blog\ValueObject\PostId;

interface PostRepository
{
    public function find(PostId $id): ?Post;
}
