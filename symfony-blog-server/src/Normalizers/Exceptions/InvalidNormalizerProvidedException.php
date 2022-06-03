<?php

namespace App\Normalizers\Exceptions;

use Exception;

class InvalidNormalizerProvidedException extends Exception
{
    public function __construct(mixed $invalidNormalizer)
    {
        parent::__construct("An object of class " . $invalidNormalizer::class . " was passed to the serializer!", 500);
    }
}
