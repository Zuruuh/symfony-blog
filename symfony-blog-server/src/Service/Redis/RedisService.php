<?php

namespace App\Service\Redis;

use App\Service\Redis\Exception\InvalidRedisInstanceFetchedException;
use App\Service\Redis\Exception\MissingRedisConfigException;
use App\Service\Redis\Exception\RedisException;
use Predis\Client as Redis;
use function Symfony\Component\String\u;

class RedisService
{
    private const EXISTING_INSTANCES = ['store', 'cache'];

    /**
     * @param string $instance Which instance of redis to connect to. For now, can be either STORE or CACHE
     *
     * @throws RedisException
     */
    public function getInstance(string $instance = 'store'): Redis
    {
        if (!in_array(strtolower($instance), self::EXISTING_INSTANCES)) {
            throw new InvalidRedisInstanceFetchedException($instance);
        }

        $key = u($instance)
            ->upper()
            ->prepend('REDIS_')
            ->append('_URL')
            ->toString();

        $url = $_ENV[$key] ?? false;
        if (!$url) {
            throw new MissingRedisConfigException($instance, $key);
        }

        [$protocol, $host, $port] = preg_split('/(:\/\/)|(:)/', $url);
        return new Redis([
            'scheme' => $protocol,
            'host' => $host,
            'port' => $port,
        ]);
    }
}