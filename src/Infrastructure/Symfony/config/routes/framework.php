<?php

declare(strict_types=1);

use Symfony\Component\Routing\Loader\Configurator\RoutingConfigurator;

return static function (RoutingConfigurator $routes): void {
    if ($routes->env() === 'dev') {
        $routes->import('@FrameworkBundle/Resources/config/routing/errors.xml')->prefix('/_error');
    }
};
