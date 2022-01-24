ARG VERSION

FROM composer
FROM php:${VERSION:-8.0}-fpm-alpine

COPY --from=composer /usr/bin/composer /usr/local/bin/composer

## Installing mandatory dependencies
RUN set -ex && \
    apk add --no-cache \
    ca-certificates \
    icu-libs \
    git \
    unzip \
    libzip-dev \
    libpq \
    zlib-dev && \
    apk add postgresql-dev=9.6.10-r0 --repository=http://dl-cdn.alpinelinux.org/alpine/v3.5/main

## Installing temporary dependencies
RUN apk add --no-cache --virtual .build-deps \
    $PHPIZE_DEPS \
    icu-dev && \
    docker-php-ext-install \
    intl \
    zip

## Installing apcu
RUN pecl install apcu-5.1.17 && docker-php-ext-enable apcu

## Installing required php extensions
RUN docker-php-ext-enable opcache && \
    docker-php-ext-install zip && \
    docker-php-ext-install pdo && \
    docker-php-ext-install pdo_pgsql && \
    docker-php-ext-install pgsql

## Removing temporary dependencies
RUN apk del .build-deps

## Using gosu for permissions management
RUN apk add gosu --update --no-cache --repository http://dl-3.alpinelinux.org/alpine/edge/testing/ --allow-untrusted && \
    addgroup bar && \
    adduser -D -h /home -s /bin/sh -G bar foo

## Custom PHP & Symfony config
RUN echo "error_reporting=E_ALL" > /usr/local/etc/php/conf.d/error_reporting.ini

ADD entrypoint.sh /entrypoint

ENTRYPOINT ["/entrypoint"]
