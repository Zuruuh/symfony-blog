<?php

namespace App\Manager;

use App\Entity\Post;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Security;

class PostManager
{
    public function __construct(
        private readonly EntityManagerInterface $em,
        private readonly Security $security
    ) {}

    private Post $post;

    public function forPost(Post $post): self
    {
        return (clone $this)->setPost($post);
    }

    public function setPost(Post $post): self
    {
        $this->post = $post;

        return $this;
    }

    public function save(?User $user = null): Post
    {
        $this->post->updateSlug();
        $this->post->generateTimestamps();
        $this->post->setAuthor($user ?? $this->security->getUser()); // @phpstan-ignore-line

        $this->em->persist($this->post);

        return $this->post;
    }

    public function update(): void
    {
        $this->post->updateSlug();
    }
}