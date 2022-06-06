<?php

use function \Symfony\Component\DependencyInjection\Loader\Configurator\env;
use Symfony\Config\FrameworkConfig;

return static function (FrameworkConfig $framework) {
    $framework
        ->cache()
            ->app('cache.adapter.redis')
    ;

    $framework
        ->cache()
            ->defaultRedisProvider(env('REDIS_CACHE_URL'))
    ;
};
