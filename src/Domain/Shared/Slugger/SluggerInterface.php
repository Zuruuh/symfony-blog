<?php

declare(strict_types=1);

namespace Domain\Shared\Slugger;

interface SluggerInterface
{
    public function slug(string $string): Slug;
}
