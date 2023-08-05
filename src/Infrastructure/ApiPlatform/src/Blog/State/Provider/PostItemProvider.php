<?php

declare(strict_types=1);

namespace Infrastructure\ApiPlatform\Blog\State\Provider;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProviderInterface;
use Infrastructure\ApiPlatform\Blog\Resource\PostResource;

final readonly class PostItemProvider implements ProviderInterface
{
    public function provide(Operation $operation, array $uriVariables = [], array $context = []): ?PostResource
    {
        dump($uriVariables);
        return null;
    }
}
