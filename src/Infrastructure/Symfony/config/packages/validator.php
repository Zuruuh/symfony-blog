<?php

declare(strict_types=1);

use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Symfony\Config\FrameworkConfig;

return static function (FrameworkConfig $framework, ContainerConfigurator $container): void {
    $validator = $framework->validation();

    $validator
        ->enabled(true)
        ->emailValidationMode('html5')
    ;

    if ($container->env() === 'test') {
        $validator->notCompromisedPassword()->enabled(false);
    }
};
