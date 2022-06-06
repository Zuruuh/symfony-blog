<?php

namespace App\Fixtures;

use App\Common\Fixture\EntityFixture;
use App\Entity\User;
use App\Manager\UserManager;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ObjectManager;

class UserFixtures extends EntityFixture
{
    public function __construct(
        EntityManagerInterface $em,
        private readonly UserManager $userManager
    ) {
        parent::__construct($em);
    }

    public function load(ObjectManager $em): void
    {
        $self = $this;
        $this->generate(50, function (int $i) use ($self): User {
            $user = (new User())
                ->setEmail($i . $self->faker->email())
                ->setUsername($i . $self->faker->userName())
                ->setActivated(true)
                ->setPassword('password');
            $self->userManager->forUser($user)->save();

            return $user;
        });

        $extraUsers = [
            'user' => User::USER_ROLE,
            'admin' => User::ADMIN_ROLE,
            'super-admin' => User::SUPER_ADMIN_ROLE,
        ];

        foreach ($extraUsers as $type => $role) {
            $user = (new User())
                ->setEmail($type . '@mail.com')
                ->setUsername($type)
                ->setPassword('password')
                ->addRoles($role);
            $this->userManager->forUser($user)->save();

            $extraUsers[$type] = $user;
        }

        $em->flush();
        foreach ($extraUsers as $reference => $user) {
            $this->addReference($reference, $user);
        }
    }
}
