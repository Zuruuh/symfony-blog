<?php

namespace App\Service\Redis\Exception;

class InvalidRedisInstanceFetchedException extends RedisException
{
    public function __construct(string $instance)
    {
        $message = sprintf('Could not fetch redis instance of type "%s".', $instance);

        parent::__construct($message);
    }
}
