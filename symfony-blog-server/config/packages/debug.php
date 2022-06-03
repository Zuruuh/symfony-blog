<?php

use function Symfony\Component\DependencyInjection\Loader\Configurator\env;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Symfony\Config\DebugConfig;

return static function (DebugConfig $debug, ContainerConfigurator $container) {
    if ($container->env() === 'dev') {
        $debug->dumpDestination('tcp://' . env('VAR_DUMPER_SERVER'));
    }
};
