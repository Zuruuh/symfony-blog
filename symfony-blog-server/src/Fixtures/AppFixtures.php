<?php

namespace App\Fixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface as Hasher;

class AppFixtures extends Fixture
{
    public function __construct(
        private Hasher $hasher
    ) {
    }

    public function load(ObjectManager $em): void
    {
        for ($i = 0; $i < 15; $i++) {
            $admin = $i === 0;
            $user = (new User())
                ->setUsername(($admin ? '' : $i) . 'admin')
                ->setEmail(($admin ? '' : $i) . 'admin@mail.com');
            $password = $this->hasher->hashPassword($user, 'password');
            $user->setPassword($password);

            $em->persist($user);
        }

        $em->flush();
    }
}
