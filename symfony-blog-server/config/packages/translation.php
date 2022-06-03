<?php

use Symfony\Config\FrameworkConfig;

return static function (FrameworkConfig $framework) {
    $framework
        ->defaultLocale('en')
    ;

    $framework
        ->translator()
            ->defaultPath('%kernel.project_dir%/translations')
    ;

    $framework
        ->translator()
            ->fallbacks(['en'])
    ;
};
