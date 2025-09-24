FROM php:7.4-apache
RUN docker-php-ext-install pdo pdo_mysql
COPY ./html /var/www/html
COPY ./php.ini /usr/local/etc/php/php.ini