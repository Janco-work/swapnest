# Use official PHP Apache image
FROM php:8.2-apache

# Enable URL rewriting (optional, but common)
RUN a2enmod rewrite

# Copy your app to the Apache root
COPY . /var/www/html/

# Expose port 80 (the default for web)
EXPOSE 80
