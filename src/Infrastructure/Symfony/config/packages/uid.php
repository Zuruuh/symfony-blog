<?php

declare(strict_types=1);

use Symfony\Config\FrameworkConfig;

return static function (FrameworkConfig $framework): void {
    $framework
        ->uid()
            ->defaultUuidVersion(7)
            ->timeBasedUuidVersion(7)
    ;
};
