<?php

namespace App\Constraint\Post;

use App\Entity\Post;
use Attribute;
use App\Validator\UniqueSlugValidator;
use Symfony\Component\Validator\Constraint;

#[Attribute]
class UniqueSlugConstraint extends Constraint
{
    public const ALREADY_IN_USE = 'post.title.slug_already_in_use';
    private ?Post $post;

    public function __construct($options = null, array $groups = null, $payload = null)
    {
        $this->post = $options['post'] ?? null;
        unset($options['post']);

        parent::__construct($options, $groups, $payload);
    }

    public function validatedBy(): string
    {
        return UniqueSlugValidator::class;
    }

    public function getPost(): ?Post
    {
        return $this->post;
    }
}