<?php

declare(strict_types=1);

namespace Infrastructure\ApiPlatform\Blog\State\Processor;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProcessorInterface;
use Application\Blog\Command\CreateBlogPostCommand;
use Application\Shared\Command\CommandBusInterface;
use Domain\Blog\Model\Post;
use Domain\Blog\ValueObject\PostContent;
use Domain\Blog\ValueObject\PostTitle;
use Infrastructure\ApiPlatform\Blog\Resource\PostResource;

/**
 * @implements ProcessorInterface<PostResource>
 */
final readonly class CreatePostProcessor implements ProcessorInterface
{
    public function __construct(
        private CommandBusInterface $commandBus,
    ) {}

    public function process(mixed $data, Operation $operation, array $uriVariables = [], array $context = []): PostResource
    {
        assert($data instanceof PostResource);
        $post = $this->commandBus->dispatch(new CreateBlogPostCommand(
            new PostTitle($data->title),
            new PostContent($data->content),
        ));
        assert($post instanceof Post);

        return PostResource::fromModel($post);
    }
}
