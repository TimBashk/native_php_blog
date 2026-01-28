FROM php:8.1-fpm-bullseye

RUN apt-get update && apt-get install -y \
    libonig-dev \
    libzip-dev \
    zip \
    unzip \
    git \
    && docker-php-ext-install pdo pdo_mysql mbstring

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer
WORKDIR /var/www/html
