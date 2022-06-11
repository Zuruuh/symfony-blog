<?php

use function Symfony\Component\DependencyInjection\Loader\Configurator\env;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

if (class_exists('\Symfony\Config\DebugConfig')) {
    return static function (\Symfony\Config\DebugConfig $debug, ContainerConfigurator $container) {
        if ($container->env() === 'dev') {
            $debug->dumpDestination('tcp://' . env('VAR_DUMPER_SERVER'));
        }
    };
}

return static function () {
};
