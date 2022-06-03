<?php

use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Symfony\Config\FrameworkConfig;

return static function (FrameworkConfig $framework, ContainerConfigurator $container) {
    $framework->router()->utf8(true);

    if ($container->env() === 'prod') {
        $framework->router()->strictRequirements(null);
    }
};

