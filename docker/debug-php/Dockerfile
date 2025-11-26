FROM php:8.3.0-fpm as base-php
WORKDIR /var/www
RUN apt-get update && \
    apt-get install -y unzip libicu-dev libzip-dev zlib1g-dev git libfreetype6-dev poppler-utils libpng-dev && \
    docker-php-ext-install intl zip opcache mysqli pdo pdo_mysql bcmath gd
COPY config/php.ini /usr/local/etc/php/php.ini
COPY config/php-fpm.d/www.conf /usr/local/etc/php-fpm.d/www.conf
COPY --from=composer:2.0.9 /usr/bin/composer /usr/local/bin/composer
FROM base-php as debug-php
RUN pecl install xdebug-3.3.1 \
    && docker-php-ext-enable xdebug
COPY conf.d/xdebug.ini /usr/local/etc/php/conf.d/xdebug.ini