FROM php:7.3-apache

COPY ./ /home/tabescore
WORKDIR /home/tabescore

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer
COPY infra/apache/apache.conf /etc/apache2/sites-available/000-default.conf
COPY infra/php/php.ini /usr/local/etc/php/conf.d/default.ini

RUN docker-php-ext-install pdo_mysql \
    && apt-get update && apt-get install -y \
        git \
        unzip \
        libpng-dev \
    && pecl install xdebug \
    && a2enmod rewrite && service apache2 restart \
    && composer install --no-interaction \
    && docker-php-ext-enable xdebug