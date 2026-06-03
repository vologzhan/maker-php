FROM php:8.5-fpm-alpine

RUN addgroup -g 1000 app \
 && adduser -D -u 1000 -G app app

RUN apk add --no-cache \
    bash \
    git \
    unzip \
    zip \
    curl \
    linux-headers \
    postgresql-dev \
    $PHPIZE_DEPS

RUN docker-php-ext-install pdo pdo_pgsql pgsql

# xdebug versions: https://xdebug.org/download
RUN pecl install xdebug-3.5.1 \
    && docker-php-ext-enable xdebug

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /app
