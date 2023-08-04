<?php

declare(strict_types=1);

use Symfony\Config\Framework\RouterConfig;
use Symfony\Config\FrameworkConfig;

return static function (FrameworkConfig $framework): void {
    $router = $framework->router();
    assert($router instanceof RouterConfig);

    $router
        ->utf8(true)
        ->strictRequirements(true)
    ;
};
