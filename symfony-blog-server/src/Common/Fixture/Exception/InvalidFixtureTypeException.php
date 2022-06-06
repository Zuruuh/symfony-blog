<?php

namespace App\Common\Fixture\Exception;

use Exception;

class InvalidFixtureTypeException extends Exception
{
    public function __construct(string $expectedClass, mixed $object)
    {
        $message = sprintf('Expected fixture of type "%s", got "%s" instead.', $expectedClass, get_class($object));

        parent::__construct($message);
    }
}
