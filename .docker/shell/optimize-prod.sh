#!/bin/bash

# Autoloader Optimization
composer install --optimize-autoloader --no-dev

# Optimizing Configuration Loading
php artisan config:cache

# Optimizing Route Loading
php artisan route:cache

# Optimizing View Loading
php artisan view:cache
