#!/usr/bin/env bash
# Exit on error
set -o errexit

# Install PHP dependencies
composer install --no-dev --optimize-autoloader

# Clear and cache config and routes
php artisan config:clear
php artisan route:clear
php artisan view:clear
php artisan cache:clear

# Storage link
php artisan storage:link

# Database migrations
php artisan migrate --force 