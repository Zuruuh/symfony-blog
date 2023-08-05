<?php

declare(strict_types=1);

namespace Infrastructure\Symfony;

use Application\Shared\Command\CommandHandlerInterface;
use Application\Shared\Query\QueryHandlerInterface;
use Symfony\Bundle\FrameworkBundle\Kernel\MicroKernelTrait;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Kernel as BaseKernel;

final class Kernel extends BaseKernel
{
    use MicroKernelTrait;

    public function getProjectDir(): string
    {
        return dirname(__DIR__);
    }

    /**
     * @override
     */
    public function getCacheDir(): string
    {
        return dirname(__DIR__, 4) . "/.cache/symfony/{$this->environment}";
    }

    protected function build(ContainerBuilder $container): void
    {
        $container
            ->registerForAutoconfiguration(CommandHandlerInterface::class)
            ->addTag('messenger.message_handler', ['bus' => 'command.bus'])
        ;

        $container
            ->registerForAutoconfiguration(QueryHandlerInterface::class)
            ->addTag('messenger.message_handler', ['bus' => 'query.bus'])
        ;
    }
}
