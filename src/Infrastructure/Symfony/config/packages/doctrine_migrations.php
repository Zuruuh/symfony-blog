<?php

declare(strict_types=1);

use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Symfony\Config\DoctrineMigrationsConfig;

return static function (DoctrineMigrationsConfig $doctrineMigrations, ContainerConfigurator $container): void {
    $doctrineMigrations
        ->migrationsPath('DoctrineMigrations', dirname(__DIR__, 4) . '/Infrastructure/Doctrine/migrations')
        ->enableProfiler($container->env() === 'dev')
    ;
};
