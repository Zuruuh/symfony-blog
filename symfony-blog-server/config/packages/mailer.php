<?php

use function Symfony\Component\DependencyInjection\Loader\Configurator\env;
use Symfony\Config\FrameworkConfig;

return static function (FrameworkConfig $framework) {
    $framework
        ->mailer()
            ->dsn(env('MAILER_DSN'))
    ;
};
