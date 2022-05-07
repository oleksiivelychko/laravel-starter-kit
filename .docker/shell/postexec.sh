#!/bin/bash

php artisan key:generate --ansi
php artisan migrate:refresh --force
php artisan db:seed --force
php artisan storage:link
php artisan test-mail
