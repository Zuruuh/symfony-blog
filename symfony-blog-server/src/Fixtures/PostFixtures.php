<?php

namespace App\Fixtures;

use App\Common\EntityFixture;
use App\Entity\Post;
use App\Entity\User;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class PostFixtures extends EntityFixture implements DependentFixtureInterface
{
    public function load(ObjectManager $em): void
    {
        $this->generate(50, function (int $i): Post {
            $author = $this->getReference(User::class . "-$i");
            if (!$author instanceof User) {
                throw new \RuntimeException();
            }

            return (new Post())
                ->setAuthor($author)
                ->setContent($this->faker->text())
                ->setSlug("$i-" . $this->faker->slug())
                ->setTitle($this->faker->sentence());
        });
    }

    public function getDependencies(): array
    {
        return [
            UserFixtures::class
        ];
    }
}