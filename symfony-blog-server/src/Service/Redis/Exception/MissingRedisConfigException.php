<?php

namespace App\Service\Redis\Exception;

class MissingRedisConfigException extends RedisException
{
    public function __construct(string $instance, string $parameterKey)
    {
        $message = sprintf('Could not find parameter key for redis instance "%s" with parameter key "%s". Make sure it exists in the correct .env file', $instance, $parameterKey);

        parent::__construct($message);
    }
}
