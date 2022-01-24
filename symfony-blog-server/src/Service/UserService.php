<?php

namespace App\Service;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface as Hasher;


class UserService
{
    public function __construct(
        private Hasher $hasher,
        private EntityManagerInterface $em
    ) {
    }

    public function updatePassword(User &$user, string $password): void
    {
        $hashedPassword = $this->hasher->hashPassword($user, $password);
        $user->setPassword($hashedPassword);
    }

    public function save(User $user): void
    {
        $this->em->persist($user);
        $this->em->flush();
    }
}
