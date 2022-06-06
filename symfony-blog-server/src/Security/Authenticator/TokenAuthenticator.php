<?php

declare(strict_types=1);

namespace App\Security\Authenticator;

use App\Repository\UserRepository;
use App\Service\Auth\JWTService;
use Doctrine\ORM\NoResultException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Exception\BadCredentialsException;
use Symfony\Component\Security\Core\Exception\CredentialsExpiredException;
use Symfony\Component\Security\Http\Authenticator\AbstractAuthenticator;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Passport;
use Symfony\Component\Security\Http\Authenticator\Passport\SelfValidatingPassport;
use Symfony\Contracts\Translation\TranslatorInterface;

class TokenAuthenticator extends AbstractAuthenticator
{
    public function __construct(
        private readonly UserRepository      $userRepository,
        private readonly TranslatorInterface $translator,
        private readonly JWTService          $JWTService
    ) {}

    public function supports(Request $request): ?bool
    {
        return $request->headers->has('Authorization');
    }

    public function authenticate(Request $request): Passport
    {
        $token = $request->headers->get('Authorization');
        $token = substr($token, 7);

        $payload = $this->JWTService->decode($token);
        if (!$payload) {
            $json = json_encode(['message' => $this->translator->trans('auth.invalid_credentials', [], 'errors')]);

            throw new BadCredentialsException($json, 401);
        }

        if ($payload['exp'] < (new \DateTime())->getTimestamp()) {
            $json = json_encode(['message' => $this->translator->trans('auth.expired_credentials', [], 'errors')]);

            throw new CredentialsExpiredException($json, 401);
        }
        $self = $this;

        return new SelfValidatingPassport(new UserBadge($payload['user'] ?? '', function ($userIdentifier) use ($self, $payload) {
            try {
                $user = $self->userRepository->findByUniqueIdentifier($userIdentifier);
            } catch (NoResultException $e) {
                $message = $self->translator->trans('auth.invalid_credentials', [], 'errors');

                throw new BadCredentialsException(json_encode(['message' => $message]), 401);
            }

            if ($user->getJwtSalt() !== $payload['salt']) {
                $json = json_encode(['message' => $this->translator->trans('auth.expired_credentials', [], 'errors')]);

                throw new CredentialsExpiredException($json, 401);
            }

            return $user;
        }));
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $firewallName): ?Response
    {
        return null;
    }

    public function onAuthenticationFailure(Request $request, AuthenticationException $exception): ?Response
    {
        return new Response($exception->getMessage(), 400, ['Content-Type' => 'application/json']);
    }
}
