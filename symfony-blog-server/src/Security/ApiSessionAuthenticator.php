<?php

namespace App\Security;

use App\Repository\UserRepository;
use Predis\Client as Redis;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Http\Authenticator\AbstractAuthenticator;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Passport;
use Symfony\Component\Security\Http\Authenticator\Passport\SelfValidatingPassport;

class ApiSessionAuthenticator extends AbstractAuthenticator
{
    public const AUTH_SESSID_COOKIE = 'sessid';

    public function __construct(
        private Redis $redis,
        private UserRepository $userRepository
    ) {
    }

    public function supports(Request $request): bool
    {
        return true;
    }

    public function authenticate(Request $request): ?Passport
    {
        $sessid = $request->cookies->get(self::AUTH_SESSID_COOKIE);

        if ($sessid) {
            $userId = $this->redis->get($sessid);
            if ($userId) {
                $user = $this->userRepository->findOneBy(['id' => $userId]);
                if ($user) {
                    return new SelfValidatingPassport(new UserBadge($user->getUserIdentifier()));
                }
            }
        }

        throw new UnauthorizedHttpException('login', 'You need to be logged in to perform this action');
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $firewallName): ?Response
    {
        return null;
    }

    public function onAuthenticationFailure(Request $request, AuthenticationException $exception): ?Response
    {
        $data = [
            // you may want to customize or obfuscate the message first
            'message' => strtr($exception->getMessageKey(), $exception->getMessageData())

            // or to translate this message
            // $this->translator->trans($exception->getMessageKey(), $exception->getMessageData())
        ];

        return new JsonResponse($data, Response::HTTP_UNAUTHORIZED);
    }
}
