<?php

use Monolog\Formatter\JsonFormatter;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Symfony\Config\MonologConfig;

return static function (MonologConfig $monolog, ContainerConfigurator $container) {
    $monolog->channels(['deprecation']);

    if ($container->env() === 'dev') {
        $monolog
            ->handler('main')
                ->type('stream')
                ->path('%kernel.logs_dir%/%kernel.environment%.log')
                ->level('debug')
                ->channels()
                    ->elements(['!event'])
        ;

        $monolog
            ->handler('console')
                ->type('console')
                ->processPsr3Messages(false)
                ->channels()
                    ->elements(['!event', '!doctrine', '!console'])
        ;
    }

    if ($container->env() === 'test') {
        $monolog
            ->handler('main')
                ->type('fingers_crossed')
                ->actionLevel('error')
                ->handler('nested')
                ->excludedHttpCode([404, 405])
                ->channels()
                    ->elements(['!event'])
        ;

        $monolog
            ->handler('nested')
                ->type('stream')
                ->path('%kernel.logs_dir%/%kernel.environment%.log')
                ->level('debug')
        ;
    }

    if ($container->env() === 'prod') {
        $monolog
            ->handler('main')
                ->type('fingers_crossed')
                ->handler('nested')
                ->excludedHttpCode([404, 405])
                ->bufferSize(50)
        ;

        $monolog
            ->handler('nested')
                ->type('stream')
                ->path('php://stderr')
                ->level('debug')
                ->formatter(JsonFormatter::class)
            ;

        $monolog
            ->handler('console')
                ->type('console')
                ->processPsr3Messages(false)
                ->channels()
                    ->elements(['!event', '!doctrine'])
        ;

        $monolog
            ->handler('deprecation')
                ->type('stream')
                ->path('php://stderr')
                ->channels()
                    ->elements('deprecation')
        ;
    }
};
