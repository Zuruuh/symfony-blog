<?php

declare(strict_types=1);

use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Symfony\Config\DoctrineConfig;
use Symfony\Config\Doctrine\DbalConfig;

return static function (DoctrineConfig $doctrine, ContainerConfigurator $container): void {
    $dbal = $doctrine->dbal();

    $dbal->type('url', '%env(resolve:APP_DATABASE_URL)%');
    $dbal->type('server_version', '15');
    # orm:
    #     auto_generate_proxy_classes: true
    #     enable_lazy_ghost_objects: true
    #     report_fields_where_declared: true
    #     validate_xml_mapping: true
    #     naming_strategy: doctrine.orm.naming_strategy.underscore_number_aware
    #     auto_mapping: true

    if ($container->env() === 'test') {
        $doctrine->dbal(['dbname_suffix' => '_test%env(default::TEST_TOKEN)%']);
    }

/**
when@prod:
    doctrine:
        # orm:
        #     auto_generate_proxy_classes: false
        #     proxy_dir: '%kernel.build_dir%/doctrine/orm/Proxies'
        #     query_cache_driver:
        #         type: pool
        #         pool: doctrine.system_cache_pool
        #     result_cache_driver:
        #         type: pool
        #         pool: doctrine.result_cache_pool

    framework:
        cache:
            pools:
                doctrine.result_cache_pool:
                    adapter: cache.app
                doctrine.system_cache_pool:
                    adapter: cache.system
*/
};
