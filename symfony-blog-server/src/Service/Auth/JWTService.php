<?php

namespace App\Service\Auth;

use App\Config\Environment;
use App\Entity\User;
use App\Repository\UserRepository;
use App\Security\Exceptions\MissingJWTKeyPairException;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Firebase\JWT\SignatureInvalidException;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Security\Core\User\UserInterface;

class JWTService
{
    public const KEY_ALGORITHM = 'RS256';

    private string $privateKey;
    private Key $publicKey;

    /**
     * @throws MissingJWTKeyPairException
     */
    public function __construct(
        private readonly RequestStack   $requestStack,
        private readonly UserRepository $userRepository,
        private readonly Environment    $environment
    ) {
        $privateKeyPath = $this->environment->path('%s/config/jwt/private.pem');
        $publicKeyPath = $this->environment->path('%s/config/jwt/public.pem');

        if (!file_exists($privateKeyPath) || !file_exists($publicKeyPath)) {
            throw new MissingJWTKeyPairException();
        }

        $this->privateKey = file_get_contents($privateKeyPath);
        $this->publicKey = new Key(file_get_contents($publicKeyPath), self::KEY_ALGORITHM);
    }

    public function generate(UserInterface $user): string
    {
        return JWT::encode([
            // Registered claims
            'iss' => $this->requestStack->getCurrentRequest()->getHost(),
            'iat' => (new \DateTime('now'))->getTimestamp(),
            'exp' => $this->environment->isEnv('prod') ? (new \DateTime('+1 hour'))->getTimestamp() : (new \DateTime('+9999 days'))->getTimestamp(),
            // Private claims
            'user' => $user->getUserIdentifier(),
            'salt' => $user->getJwtSalt(), // @phpstan-ignore-line
        ], $this->privateKey, self::KEY_ALGORITHM);
    }

    public function decode(string $token): ?array
    {
        try {
            return (array) JWT::decode($token, $this->publicKey);
        } catch (\Exception $e) {
            return null;
        }
    }

    public function getUserFromToken(string $token): ?User
    {
        $userIdentifier = $this->decode($token)['user'];

        return $this->userRepository->findByUniqueIdentifier($userIdentifier);
    }
}