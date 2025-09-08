FROM php:8.1-apache

# Install PDO SQLite extension
RUN docker-php-ext-install pdo pdo_sqlite

COPY . /var/www/html
WORKDIR /var/www/html

RUN composer install || true
