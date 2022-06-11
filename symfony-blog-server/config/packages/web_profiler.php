<?php

use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Symfony\Config\WebProfilerConfig;
use Symfony\Config\FrameworkConfig;

if (class_exists('\Symfony\Config\WebProfilerConfig')) {
    return static function (WebProfilerConfig $profiler, FrameworkConfig $framework, ContainerConfigurator $container) {
        if ($container->env() === 'dev') {
            $profiler->toolbar(true);
            $profiler->interceptRedirects(false);
            $framework->profiler()->onlyExceptions(false);
        }

        if ($container->env() === 'test') {
            $profiler->toolbar(false);
            $profiler->interceptRedirects(false);
            $framework->profiler()->collect(false);
        }
    };
}

return static function () {
};
