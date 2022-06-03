<?php

namespace App\Common\Paging\Exceptions;

use Symfony\Component\HttpKernel\Log\Logger;

class RequestStackUnavailableException extends \Exception
{
    public function __construct()
    {
        (new Logger())->critical("Could not access request stack!");

        parent::__construct("Could not access request stack!", 500);
    }
}