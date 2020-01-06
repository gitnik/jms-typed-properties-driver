FROM php:7.4-cli
WORKDIR /app
RUN pecl install xdebug-2.8.1 && docker-php-ext-enable xdebug
COPY dev/xdebug.ini /usr/local/etc/php/conf.d/xdebug.ini
