<?php

namespace Infrastructure\Symfony\Blog\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\AsController;

#[AsController]
final readonly class CreatePostController
{
    public function __invoke(Request $request): Response
    {
        die();
    }
}
