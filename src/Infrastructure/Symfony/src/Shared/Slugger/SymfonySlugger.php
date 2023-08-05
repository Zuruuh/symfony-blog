<?php

declare(strict_types=1);

namespace Infrastructure\Symfony\Shared\Slugger;

use Domain\Shared\Slugger\Slug;
use Domain\Shared\Slugger\SluggerInterface;
use Symfony\Component\String\Slugger\SluggerInterface as SymfonySluggerInterface;

final readonly class SymfonySlugger implements SluggerInterface
{
    public function __construct(private SymfonySluggerInterface $slugger) {}

    public function slug(string $string): Slug
    {
        return new Slug($this->slugger->slug($string)->toString());
    }
}
