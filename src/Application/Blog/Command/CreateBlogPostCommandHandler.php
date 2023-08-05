<?php

declare(strict_types=1);

namespace Application\Blog\Command;

use Application\Shared\Command\CommandHandlerInterface;
use Domain\Blog\Model\Post;
use Domain\Blog\Persistence\PostPersister;
use Domain\Blog\ValueObject\PostId;

final readonly class CreateBlogPostCommandHandler implements CommandHandlerInterface
{
    public function __construct(private PostPersister $postPersister) {}

    public function __invoke(CreateBlogPostCommand $command): Post
    {
        $post = new Post(
            new PostId(),
            $command->title,
            $command->content,
        );

        $this->postPersister->persist($post);

        return $post;
    }
}
