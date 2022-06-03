<?php

use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Symfony\Config\FrameworkConfig;

return static function (FrameworkConfig $framework, ContainerConfigurator $container) {
    $framework
        ->validation()
            ->emailValidationMode('html5')
    ;

    if ($container->env() === 'test') {
        $framework
            ->validation()
                ->notCompromisedPassword(false)
        ;
    }
};
