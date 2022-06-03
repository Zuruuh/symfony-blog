<?php

namespace App;

use Symfony\Bundle\FrameworkBundle\Kernel\MicroKernelTrait;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Symfony\Component\HttpKernel\Kernel as BaseKernel;

class Kernel extends BaseKernel
{
    use MicroKernelTrait;

    private array $configFiles = ['routes', 'services'];

    private function configureContainer(ContainerConfigurator $container): void
    {
        $configDir = $this->getConfigDir();

        $container->import($configDir . '/{packages}/*.{yaml,php}');
        $container->import($configDir . '/{packages}/' . $this->environment . '/*.{yaml,php}');

        foreach($this->configFiles as $config) {
            $file = $configDir . "/$config";

            if (is_file($file . '.yaml')) {
                $container->import($file . '.yaml');
                if (is_file("$file" . "_$this->environment.yaml")) {
                    $container->import($file . "_$this->environment.yaml");
                }
            } else {
                $container->import("$file.php");
            }
        }
    }
}
