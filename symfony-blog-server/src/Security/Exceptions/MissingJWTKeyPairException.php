<?php

namespace App\Security\Exceptions;

class MissingJWTKeyPairException extends \Exception
{
    public function __construct()
    {
        parent::__construct("Missing JWT public or private key pair. Generate a new pair by running `php bin/console security:generate-key-pair`", 500);
    }
}
