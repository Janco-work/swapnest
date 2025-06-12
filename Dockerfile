FROM php:8.2-apache

RUN docker-php-ext-install pdo pdo_mysql

RUN a2enmod rewrite

COPY . /var/www/html/

RUN chmod -R 755 /var/www/html

EXPOSE 80

RUN php -m
