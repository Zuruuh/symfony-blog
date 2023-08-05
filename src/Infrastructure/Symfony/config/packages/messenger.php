<?php

declare(strict_types=1);

use Application\Shared\Command\CommandInterface;
use Application\Shared\Query\QueryInterface;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Symfony\Config\Framework\Messenger\RoutingConfig;
use Symfony\Config\Framework\Messenger\TransportConfig;
use Symfony\Config\FrameworkConfig;

return static function (FrameworkConfig $framework, ContainerConfigurator $container): void {
    $messenger = $framework->messenger();

    $messenger->defaultBus('queue.bus');
    $messenger->bus('queue.bus');
    $messenger->bus('delayed.bus');
    $messenger->bus('command.bus');
    $messenger->bus('query.bus');

    $sync = $messenger->transport('sync');
    assert($sync instanceof TransportConfig);
    $sync->dsn('sync://');

    $async = $messenger->transport('async');
    assert($async instanceof TransportConfig);
    $async->dsn($container->env() === 'test' ? 'in-memory://' : '%env(APP_MESSENGER_TRANSPORT_DSN)%');

    foreach ([QueryInterface::class, CommandInterface::class] as $class) {
        $routing = $messenger->routing($class);
        assert($routing instanceof RoutingConfig);

        $routing->senders(['sync']);
    }
};