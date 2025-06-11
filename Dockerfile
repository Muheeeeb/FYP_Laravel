FROM php:8.2-apache

# Install system dependencies
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip \
    libzip-dev

# Clear cache
RUN apt-get clean && rm -rf /var/lib/apt/lists/*

# Install PHP extensions
RUN docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd zip

# Enable Apache modules
RUN a2enmod rewrite headers

# Configure PHP with maximum error reporting
RUN echo "display_errors = On" > /usr/local/etc/php/conf.d/error-logging.ini \
    && echo "error_reporting = E_ALL" >> /usr/local/etc/php/conf.d/error-logging.ini \
    && echo "log_errors = On" >> /usr/local/etc/php/conf.d/error-logging.ini \
    && echo "error_log = /dev/stderr" >> /usr/local/etc/php/conf.d/error-logging.ini

# Get latest Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /var/www/html

# Copy the application
COPY . .

# Create storage structure with full permissions
RUN mkdir -p /var/www/html/storage/framework/sessions \
    && mkdir -p /var/www/html/storage/framework/views \
    && mkdir -p /var/www/html/storage/framework/cache \
    && mkdir -p /var/www/html/storage/logs \
    && chmod -R 777 /var/www/html/storage \
    && chmod -R 777 /var/www/html/bootstrap/cache

# Install dependencies
RUN composer install --no-dev --optimize-autoloader

# Set ServerName in Apache
RUN echo "ServerName localhost" >> /etc/apache2/apache2.conf

# Apache configuration
COPY apache.conf /etc/apache2/sites-available/000-default.conf

# Create startup script
RUN echo '#!/bin/bash\n\
php artisan config:clear\n\
php artisan cache:clear\n\
php artisan migrate --force\n\
apache2-foreground' > /usr/local/bin/start.sh \
    && chmod +x /usr/local/bin/start.sh

# Set the startup command
CMD ["/usr/local/bin/start.sh"] 