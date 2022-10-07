FROM php:8-apache
RUN cp /etc/apache2/mods-available/rewrite.load /etc/apache2/mods-enabled/
RUN docker-php-ext-install pdo pdo_mysql

COPY --from=composer:latest /usr/bin/composer /usr/local/bin/composer
RUN apt-get update && \
    apt-get install -y --no-install-recommends git zip

WORKDIR /var/www/html
COPY . .
RUN composer install
