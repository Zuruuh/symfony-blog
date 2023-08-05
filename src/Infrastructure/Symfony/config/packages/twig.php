<?php

declare(strict_types=1);

use Symfony\Config\TwigConfig;

return static function (TwigConfig $twig): void {
    $twig
        ->defaultPath('%kernel.project_dir%/../Twig/templates')
        ->strictVariables(true)
    ;
};
