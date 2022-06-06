<?php

namespace App\Message\Common;

use App\Common\Message\AsyncMessageInterface;
use App\Mail\Mail;

class MailMessage implements AsyncMessageInterface
{
    public function __construct(
        private readonly Mail $email
    ) {}

    public function getEmail(): Mail
    {
        return $this->email;
    }
}
