FROM php:8.0.9-apache

USER root

COPY . /var/www/html

WORKDIR /var/www/html

RUN apt-get update && apt-get install -y \
        libpng-dev \
        zlib1g-dev \
        libxml2-dev \
        libzip-dev \
        libonig-dev \
        zip \
        curl \
        unzip \
    && docker-php-ext-configure gd \
    && docker-php-ext-install -j$(nproc) gd \
    && docker-php-ext-install pdo_mysql \
    && docker-php-ext-install mysqli \
    && docker-php-ext-install zip \
    && docker-php-source delete

# COPY .docker/vhost.conf /etc/apache2/sites-available/000-default.conf

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
RUN composer install

# RUN chown -R www-data:www-data /var/www/html \
#    && a2enmod rewrite

ENTRYPOINT  bash -c "sleep 5 && php artisan migrate && php artisan serve --host=0.0.0.0 --port=80"
