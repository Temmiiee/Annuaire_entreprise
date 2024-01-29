FROM php:8.2-fpm

RUN apt update
RUN apt install -y git zip unzip libcurl4-openssl-dev libssl-dev php-pear php-dev

RUN docker-php-ext-install pdo_mysql
RUN pecl install xdebug pecl_http \
    && docker-php-ext-enable xdebug http

# Installation de composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer
ENV COMPOSER_ALLOW_SUPERUSER=1