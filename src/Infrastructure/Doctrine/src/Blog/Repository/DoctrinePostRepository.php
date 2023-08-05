<?php

declare(strict_types=1);

namespace Infrastructure\Doctrine\Blog\Repository;

use Doctrine\DBAL\Types\Types;
use Domain\Blog\Model\Post;
use Domain\Blog\Persistence\PostPersister;
use Domain\Blog\Repository\PostRepository;
use Domain\Blog\ValueObject\PostId;
use Infrastructure\Doctrine\Shared\Repository\DoctrineRepository;
use Symfony\Bridge\Doctrine\Types\UuidType;

/**
 * @extends DoctrineRepository<Post>
 */
final class DoctrinePostRepository extends DoctrineRepository implements PostRepository, PostPersister
{
    public const TABLE = 'posts';

    public function find(PostId $id): ?Post
    {
        return null;
    }

    public function persist(Post $post): void
    {
        $this->connection->insert(
            self::TABLE,
            [
                'id' => $post->id->__toString(),
                'title' => $post->getTitle()->__toString(),
                'slug' => $post->getSlug()->__toString(),
                'content' => $post->getContent()->__toString(),
            ],
            [
                'id' => UuidType::NAME,
                'title' => Types::STRING,
                'slug' => Types::TEXT,
                'content' => Types::TEXT,
            ],
        );
    }
}
