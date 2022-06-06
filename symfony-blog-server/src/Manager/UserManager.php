<?php

namespace App\Manager;

use App\Common\Message\AmqpMessageBus;
use App\Entity\User;
use App\Mail\Security\ValidateAccountMail;
use App\Message\Common\MailMessage;
use App\Service\Auth\UserAuthService;
use Doctrine\ORM\EntityManagerInterface;
use Ramsey\Uuid\Uuid;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface as Hasher;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use function Symfony\Component\String\u;

class UserManager
{
    public function __construct(
        private readonly EntityManagerInterface $em,
        private readonly Hasher                 $hasher,
        private readonly UserAuthService        $userAuthService,
        private readonly UrlGeneratorInterface  $urlGenerator,
        private readonly AmqpMessageBus         $messageBus,
        private readonly RequestStack           $requestStack,
    ) {}

    private User $user;

    public function forUser(User $user): self
    {
        return (clone $this)->setUser($user);
    }

    public function setUser(User $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function save(): User
    {
        $this
            ->updatePassword()
            ->updateJWTSalt()
        ;

        $this->em->persist($this->user);

        return $this->user;
    }

    public function update(): User
    {
        $this->updateJWTSalt();

        return $this->user;
    }

    private function updateJWTSalt(): self
    {
        $salt = Uuid::uuid4()->toString();
        $salt = u($salt)
            ->replace('-', '')
            ->truncate(16)
            ->toString();
        $this->user->setJwtSalt($salt);

        return $this;
    }

    private function updatePassword(): self
    {
        $password = $this->user->getPassword();
        $password = $this->hasher->hashPassword($this->user, $password);
        $this->user->setPassword($password);

        return $this;
    }

    public function sendVerificationEmail(): self
    {
        $token = $this->userAuthService->generateVerifyAccountToken($this->user);
        $link = $this->urlGenerator->generate('app_security_security_verifyaccount', [
            'token' => $token,
        ], UrlGeneratorInterface::ABSOLUTE_URL);

        $locale = $this->requestStack->getMainRequest()->getLocale();
        $mail = new ValidateAccountMail($locale, $this->user, $link);
        $message = new MailMessage($mail);
        $this->messageBus->dispatch($message);

        return $this;
    }
}
