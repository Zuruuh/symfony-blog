<?php

declare(strict_types=1);

use Symfony\Component\Dotenv\Dotenv;

fakeFunctionJustToTestCI();

require dirname(__DIR__) . '/vendor/autoload.php';

(new Dotenv())->bootEnv(dirname(__DIR__) . '/.env');
