#!/usr/bin/env bash
set -e

APP_DIR=/var/www/html

cd "$APP_DIR"

echo "Starting Laravel application setup..."

# Install Composer dependencies
echo "Installing Composer dependencies..."
composer install --no-interaction --optimize-autoloader --prefer-dist

# Ensure permissions
chown -R www-data:www-data storage bootstrap/cache || true
chmod -R 775 storage bootstrap/cache || true

# Ensure env
if [ ! -f .env ]; then
  echo "Creating .env file..."
  cp .env.example .env || true
fi

# Configure DB from env if needed (Laravel reads from .env)
echo "Generating application key..."
php artisan key:generate --force || true

# Wait for database to be ready
echo "Waiting for database connection..."
until nc -z db 3306; do
  echo "Database is unavailable - sleeping"
  sleep 2
done
echo "Database is ready!"

# Migrate and seed
echo "Running migrations..."
php artisan migrate:fresh --force || true

echo "Seeding database..."
php artisan db:seed --force || true

echo "Laravel setup completed. Starting PHP-FPM..."
exec php-fpm
