<?php

use App\Controller\Common\ExceptionController;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use function Symfony\Component\DependencyInjection\Loader\Configurator\env;
use Symfony\Config\FrameworkConfig;

return static function (FrameworkConfig $framework, ContainerConfigurator $container) {
    $framework
        ->secret(env('APP_SECRET'))
        ->httpMethodOverride(false)
        ->csrfProtection()->enabled(false);
    ;

    $framework
        ->session()
        ->enabled(false)
    ;

    $framework
        ->errorController(ExceptionController::class . '::show')
        ->phpErrors()
            ->log(true)
    ;

    if ($container->env() === 'test') {
        $framework->test(true);
    }
};
