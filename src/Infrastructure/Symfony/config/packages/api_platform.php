<?php

declare(strict_types=1);

use Symfony\Config\ApiPlatformConfig;

return static function (ApiPlatformConfig $api): void {
    $api->mapping()->paths([
        dirname(__DIR__, 3) . '/ApiPlatform/src/Blog/Resource',
    ]);
};
