FROM php:7.4-apache
WORKDIR /var/www/html
COPY src .
RUN cp /etc/apache2/mods-available/rewrite.load /etc/apache2/mods-enabled
RUN chmod 777 products.json

# docker build . -t php_apache
# docker run -d -p 8080:80 php_apache
# docker exec -it container_id bash
# exit
