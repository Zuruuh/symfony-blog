<?php

declare(strict_types=1);

use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

return static function (ContainerConfigurator $container): void {
    $services = $container->services()
        ->defaults()
            ->autowire()
            ->autoconfigure();

    $services
        ->load('Infrastructure\\Symfony\\', dirname(__DIR__) . '/src/')
        ->exclude(array_map(static fn (string $entry) => dirname(__DIR__) . "/src/$entry", [
            '/DependencyInjection/',
            '/Entity/',
            '/Kernel.php',
        ]));

    $services->load('Infrastructure\\Doctrine\\', dirname(__DIR__, 2) . '/Doctrine/src/');

    $services->load('Application\\', dirname(__DIR__, 3) . '/Application/');
    $services->load('Domain\\', dirname(__DIR__, 3) . '/Domain/');
};
