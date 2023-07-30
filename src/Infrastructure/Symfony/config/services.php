<?php

declare(strict_types=1);

namespace Symfony\Component\DependencyInjection\Loader\Configurator;

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
            '/Kernel.php'
        ]));
};
