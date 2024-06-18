#!/usr/bin/env bash
echo "Running composer"
composer global require hirak/prestissimo
composer install --no-dev --working-dir=/var/www/html

echo "Caching config..."
php artisan optimize:clear

echo "Running migrations..."
php artisan migrate --force

echo "Seedingg..."
php artisan db:seed
