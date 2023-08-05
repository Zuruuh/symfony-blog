<?php

declare(strict_types=1);

namespace Infrastructure\ApiPlatform\Blog\Resource;

use ApiPlatform\Metadata as Api;
use Domain\Blog\Model\Post;
use Domain\Blog\ValueObject\PostContent;
use Domain\Blog\ValueObject\PostTitle;
use Infrastructure\ApiPlatform\Blog\State\Processor\CreatePostProcessor;
use Infrastructure\ApiPlatform\Blog\State\Provider\LatestPostProvider;
use Infrastructure\ApiPlatform\Blog\State\Provider\PostItemProvider;
use Symfony\Component\Routing\Requirement\Requirement;
use Symfony\Component\Uid\AbstractUid;
use Symfony\Component\Validator\Constraints as Assert;

#[Api\ApiResource(
    shortName: 'Post',
    routePrefix: '/blog/posts',
    provider: PostItemProvider::class,
    operations: [
        /* new Api\GetCollection( */
        /*     '/posts/latests.{_format}', */
        /*     openapiContext: ['summary' => 'Find latest posts'], */
        /*     paginationEnabled: false, */
        /*     provider: LatestPostProvider::class, */
        /* ), */

        new Api\Get(
            uriTemplate: '/{id}',
            requirements: ['id' => Requirement::UUID_V7],
            provider: PostItemProvider::class
        ),
        new Api\GetCollection(),
        new Api\Post(uriTemplate: '', processor: CreatePostProcessor::class),
    ],
)]
final class PostResource
{
    public function __construct(
        #[Api\ApiProperty(identifier: true, readable: false, writable: false)]
        public ?AbstractUid $id = null,
        #[Assert\NotBlank]
        #[Assert\Length(min: PostTitle::MIN_LENGTH, max: PostTitle::MAX_LENGTH)]
        public ?string $title = null,
        #[Assert\NotBlank]
        #[Assert\Length(min: PostContent::MIN_LENGTH, max: PostContent::MAX_LENGTH)]
        public ?string $content = null,
        public ?string $slug = null,
    ) {}

    public static function fromModel(Post $post): static
    {
        return new self(
            $post->id->value,
            $post->getTitle()->__toString(),
            $post->getContent()->__toString(),
            $post->getSlug()->__toString(),
        );
    }
}
