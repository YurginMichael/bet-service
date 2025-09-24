#!/usr/bin/env bash
set -euo pipefail

APP_DIR=/var/www/html

cd "$APP_DIR"

# Ensure permissions if Laravel exists
if [ -f artisan ]; then
  chown -R www-data:www-data storage bootstrap/cache || true
  chmod -R 775 storage bootstrap/cache || true
fi

# Start PHP-FPM
php-fpm
