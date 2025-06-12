FROM php:8.2-apache

# Install MySQL driver for PHP (pdo_mysql)
RUN docker-php-ext-install pdo pdo_mysql

# Enable .htaccess mod_rewrite
RUN a2enmod rewrite

# Copy your code
COPY . /var/www/html/

# (Optional) Set permissions
RUN chmod -R 755 /var/www/html

EXPOSE 80
