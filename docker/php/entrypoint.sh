#!/usr/bin/env bash
set -euo pipefail

APP_DIR=/var/www/html

if [ ! -f "$APP_DIR/artisan" ]; then
  composer create-project --prefer-dist laravel/laravel "$APP_DIR"
fi

cd "$APP_DIR"

# Ensure permissions
chown -R www-data:www-data storage bootstrap/cache || true
chmod -R 775 storage bootstrap/cache || true

# Ensure env
if [ ! -f .env ]; then
  cp .env.example .env || true
fi

# Configure DB from env if needed (Laravel reads from .env)
php artisan key:generate --force || true

# Install Sanctum if not present
if ! grep -q "laravel/sanctum" composer.json; then
  composer require laravel/sanctum --no-interaction --no-progress
  php artisan vendor:publish --provider="Laravel\\Sanctum\\SanctumServiceProvider"
fi

# Migrate and seed
php artisan migrate --force || true
php artisan db:seed --force || true

# Start PHP-FPM
php-fpm
