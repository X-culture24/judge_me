FROM php:8.0-apache

WORKDIR /var/www/html

# Install dependencies and PHP extensions
RUN apt-get update && apt-get install -y \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip \
    curl \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install pdo_mysql mysqli mbstring exif pcntl bcmath gd \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# Enable Apache mod_rewrite
RUN a2enmod rewrite

# Suppress FQDN warning by setting ServerName
RUN echo "ServerName localhost" >> /etc/apache2/apache2.conf

# Copy source code and wait-for-it.sh script
COPY . /var/www/html
COPY wait-for-it.sh /usr/local/bin/wait-for-it.sh

# Ensure wait-for-it is executable
RUN chmod +x /usr/local/bin/wait-for-it.sh

# Set correct ownership for Apache
RUN chown -R www-data:www-data /var/www/html
