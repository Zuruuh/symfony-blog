<?php

use function Symfony\Component\DependencyInjection\Loader\Configurator\env;
use Symfony\Config\FrameworkConfig;

const TRANSPORT = 'async';

return static function (FrameworkConfig $framework) {

    $framework
        ->messenger()
            ->transport(TRANSPORT)
                ->dsn(env('RABBITMQ_DSN'))
    ;

    $framework
        ->messenger()
//        ->routing()
    ;
};
