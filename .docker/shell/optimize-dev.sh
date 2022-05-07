#!/bin/bash

# Update the autoloader because of new classes in a classmap package
# (without having to go through an install or update)
echo "Update autoloader..."
composer dump-autoload

echo "Clear application cache..."
php artisan cache:clear

echo "Clear config cache..."
php artisan config:clear

echo "Clear route cache..."
php artisan route:clear
