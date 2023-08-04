<?php

declare(strict_types=1);

use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Symfony\Config\DoctrineMigrationsConfig;

return static function (DoctrineMigrationsConfig $doctrineMigrations, ContainerConfigurator $container): void {
    $doctrineMigrations
        ->migrationsPath('DoctrineMigrations', '%kernel.project_dir%/src/Infrastructure/Doctrine/migrations')
        ->enableProfiler($container->env() === 'dev')
    ;
};
