<?php

use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Symfony\Config\TwigConfig;

return static function (TwigConfig $twig, ContainerConfigurator $container) {
    $twig->defaultPath('%kernel.project_dir%/templates');
    $twig->path('%kernel.project_dir%/assets/images', 'images');

    if ($container->env() === 'test') {
        $twig->strictVariables(true);
    }
};
/*
twig:
    default_path: '%kernel.project_dir%/templates'

when@test:
    twig:
        strict_variables: true
*/