FROM php:8.0-apache

WORKDIR /var/www/html

# Install dependencies
RUN apt-get update && apt-get install -y \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip \
    && docker-php-ext-install pdo_mysql mysqli mbstring exif pcntl bcmath gd

# Enable mod_rewrite
RUN a2enmod rewrite

# Set ServerName to suppress FQDN warning
RUN echo "ServerName localhost" >> /etc/apache2/apache2.conf

# Copy source code to container
COPY . /var/www/html

# Set permissions for Apache
RUN chown -R www-data:www-data /var/www/html
