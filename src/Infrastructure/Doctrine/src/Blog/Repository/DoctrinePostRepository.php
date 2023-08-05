<?php

declare(strict_types=1);

namespace Infrastructure\Doctrine\Blog\Repository;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Types\Types;
use Domain\Blog\Model\Post;
use Domain\Blog\Persistence\PostPersister;
use Domain\Blog\Repository\PostRepository;
use Domain\Blog\ValueObject\PostId;
use Domain\Shared\Slugger\SluggerInterface;
use Infrastructure\Doctrine\Shared\Repository\DoctrineRepository;
use Symfony\Bridge\Doctrine\Types\UuidType;

/**
 * @extends AbstractDoctrineRepository<Post>
 */
final class DoctrinePostRepository extends DoctrineRepository implements PostRepository, PostPersister
{
    public const TABLE = 'post';

    public function __construct(
        Connection $connection,
        private SluggerInterface $slugger
    ) {
        parent::__construct($connection);
    }

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
                'slug' => $post->getTitle()->toSlug($this->slugger)->__toString(),
                'content' => $post->getContent(),
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
