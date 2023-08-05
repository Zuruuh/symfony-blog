<?php

declare(strict_types=1);

use Symfony\Component\HttpFoundation\Request;
use Symfony\Config\NelmioCorsConfig;

return static function (NelmioCorsConfig $cors): void {
    $cors
        ->defaults()
            ->originRegex(true)
            ->allowOrigin(['%env(APP_CORS_ALLOW_ORIGIN)%'])
            ->allowMethods([
                Request::METHOD_GET,
                Request::METHOD_OPTIONS,
                Request::METHOD_POST,
                Request::METHOD_PUT,
                Request::METHOD_PATCH,
                Request::METHOD_DELETE,
            ])
            ->allowHeaders(['Content-Type', 'Authorization'])
            ->exposeHeaders(['Link'])
            ->maxAge(3600)
    ;
    /* $cors->paths('^/', null); */
};
