FROM php:7.4-apache
RUN cp /etc/apache2/mods-available/rewrite.load /etc/apache2/mods-enabled

WORKDIR /var/www/html
COPY .htaccess .
COPY composer.json .

COPY --from=composer:2.3.10 /usr/bin/composer /usr/bin/composer
RUN apt-get update && \
    apt-get install -y --no-install-recommends git zip
RUN composer config -g repo.packagist composer https://packagist.org
RUN composer install

# docker build . -t php_composer --no-cache
# docker run -d -p 8080:80 -v $PWD/src:/var/www/html/src php_composer
