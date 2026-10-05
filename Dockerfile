FROM php:8.5-cli-alpine

RUN apk add --no-cache --virtual .build-deps $PHPIZE_DEPS linux-headers \
    && apk add --no-cache unzip \
    && pecl install xdebug && docker-php-ext-enable xdebug \
    && docker-php-ext-install pdo pdo_mysql \
    && apk del .build-deps \
    && rm -rf /tmp/pear

COPY --from=composer:2.2 /usr/bin/composer /usr/local/bin/composer

