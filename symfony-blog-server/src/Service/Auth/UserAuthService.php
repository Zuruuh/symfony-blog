<?php

namespace App\Service\Auth;

use App\Entity\User;
use App\Repository\UserRepository;
use App\Service\Redis\Exception\RedisException;
use App\Service\Redis\RedisService;
use DateTime;
use Predis\Client as Redis;
use Ramsey\Uuid\Uuid;
use function Symfony\Component\String\u;

class UserAuthService
{
    private readonly Redis $redis;

    /**
     * @throws RedisException
     */
    public function __construct(
        private readonly UserRepository $userRepository,
        RedisService $redisService,
    ) {
        $this->redis = $redisService->getInstance();
    }

    public function generateVerifyAccountToken(User $user): string
    {
        $key = u(Uuid::uuid4()->toString())
            ->append(Uuid::uuid4()->toString())
            ->toString()
        ;

        $this->redis->set('verify-account:' . $key, $user->getId(), 'EXAT', (new DateTime('+3 hours'))->getTimestamp());

        return $key;
    }

    public function verifyToken(string $token): bool
    {
        $key = u($token)->prepend('verify-account:')->toString();

        $user = $this->redis->get($key);
        if (!$user) {
            return false;
        }

        $this->redis->del($key);
        $user = $this->userRepository->findOneBy(['id' => $user]);

        if (!$user) {
            return false;
        }

        $user->setActivated(true);

        return true;
    }
}
