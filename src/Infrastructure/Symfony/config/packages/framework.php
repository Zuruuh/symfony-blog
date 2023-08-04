<?php

declare(strict_types=1);

use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Symfony\Config\FrameworkConfig;
use Symfony\Config\Framework\SessionConfig;

return static function (FrameworkConfig $framework, ContainerConfigurator $container): void {
    $framework->secret('%env(APP_SECRET)%');
    $framework
        ->csrfProtection()
        ->enabled(false)
    ;

    $framework
        ->httpMethodOverride(false)
        ->handleAllThrowables(false)
    ;

    $session = $framework->session();
    assert($session instanceof SessionConfig);

    $session
        ->storageFactoryId('session.storage.factory.native')
        ->handlerId(null)
        ->cookieSecure('auto')
        ->cookieSamesite('lax')
    ;

    $framework
        ->phpErrors()
            ->log(true)
    ;

    if ($container->env() === 'test') {
        $framework->test(true);
        $session->storageFactoryId('session.storage.factory.mock_file');
    }
};
