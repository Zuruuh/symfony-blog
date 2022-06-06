<?php

namespace App\Fixtures;

use App\Common\Fixture\EntityFixture;
use App\Common\Fixture\Exception\InvalidFixtureTypeException;
use App\Entity\Post;
use App\Entity\User;
use App\Manager\PostManager;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ObjectManager;

class PostFixtures extends EntityFixture implements DependentFixtureInterface
{
    public function __construct(
        EntityManagerInterface $em,
        private readonly PostManager $postManager,
    ) {
        parent::__construct($em);
    }

    public function load(ObjectManager $em): void
    {
        $this->generate(50, function (int $i): Post {
            $author = $this->getReference(User::class . "-$i");
            if (!$author instanceof User) {
                throw new InvalidFixtureTypeException(User::class, $author);
            }

            $post = (new Post())
                ->setContent($this->faker->text())
                ->setTitle($this->faker->sentence());

            return $this->postManager->forPost($post)->save($author);
        });
    }

    public function getDependencies(): array
    {
        return [
            UserFixtures::class
        ];
    }
}