#!/usr/bin/env bash
# Exit on error
set -o errexit

# Install PHP dependencies
composer install --no-interaction --prefer-dist --optimize-autoloader

# Clear cache
php artisan config:clear
php artisan cache:clear
php artisan view:clear
php artisan route:clear

# Copy .env.example to .env if .env doesn't exist
if [ ! -f ".env" ]; then
    cp .env.example .env
fi

# Generate application key
php artisan key:generate

# Storage link
php artisan storage:link

# Run database migrations
php artisan migrate --force

# Install node modules and build assets
npm install
npm run build

# Optimize
php artisan optimize 