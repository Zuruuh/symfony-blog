<?php

namespace App\Fixtures;

use App\Common\EntityFixture;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFixtures extends EntityFixture
{
    public function __construct(
        EntityManagerInterface $em,
        private UserPasswordHasherInterface $hasher
    ) {
        parent::__construct($em);
    }

    public function load(ObjectManager $manager): void
    {
        $self = $this;
        $this->generate(50, function (int $i) use ($self): User {
            $user = (new User())
                ->setEmail($i . $self->faker->email())
                ->setUsername($i . $self->faker->userName());
            $user->setPassword($self->hasher->hashPassword($user, 'password'));

            return $user;
        });
        $admin = (new User())
            ->setEmail('admin@mail.com')
            ->setUsername('admin')
            ->addRoles(User::SUPER_ADMIN_ROLE);
        $admin->setPassword($this->hasher->hashPassword($admin, 'password'));

        $manager->persist($admin);
        $manager->flush();

        $this->addReference('admin', $admin);
    }
}
