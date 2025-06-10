FROM php:8.2-apache

# Install system dependencies
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip

# Clear cache
RUN apt-get clean && rm -rf /var/lib/apt/lists/*

# Install PHP extensions
RUN docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd

# Get latest Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /var/www/html

# Copy the application
COPY . .

# Create .env file with environment variables
RUN echo "APP_NAME=HireSmart\n\
APP_ENV=production\n\
APP_DEBUG=true\n\
APP_URL=\${RENDER_EXTERNAL_URL}\n\
DB_CONNECTION=mysql\n\
DB_HOST=\${MYSQLHOST}\n\
DB_PORT=\${MYSQLPORT}\n\
DB_DATABASE=\${MYSQLDATABASE}\n\
DB_USERNAME=\${MYSQLUSER}\n\
DB_PASSWORD=\${MYSQLPASSWORD}\n\
BROADCAST_DRIVER=log\n\
CACHE_DRIVER=file\n\
FILESYSTEM_DISK=public\n\
QUEUE_CONNECTION=sync\n\
SESSION_DRIVER=file\n\
SESSION_LIFETIME=120\n\
MAIL_MAILER=smtp\n\
MAIL_HOST=smtp.gmail.com\n\
MAIL_PORT=587\n\
MAIL_USERNAME=\${MAIL_USERNAME}\n\
MAIL_PASSWORD=\${MAIL_PASSWORD}\n\
MAIL_ENCRYPTION=tls\n\
MAIL_FROM_ADDRESS=\${MAIL_FROM_ADDRESS}\n\
MAIL_FROM_NAME=\${APP_NAME}" > .env

# Install composer dependencies
RUN composer install --no-dev --optimize-autoloader

# Generate application key
RUN php artisan key:generate

# Set up storage directory
RUN mkdir -p storage/framework/{sessions,views,cache} \
    && mkdir -p storage/logs \
    && chmod -R 777 storage \
    && chmod -R 777 bootstrap/cache

# Configure Apache
COPY apache.conf /etc/apache2/sites-available/000-default.conf
RUN a2enmod rewrite

# Set proper permissions
RUN chown -R www-data:www-data /var/www/html

# Start Apache
CMD ["apache2-foreground"] 