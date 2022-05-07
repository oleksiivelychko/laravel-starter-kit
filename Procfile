web: vendor/bin/heroku-php-nginx -C .docker/nginx/heroku.conf public/
worker: php artisan queue:restart && php artisan queue:work --sleep=5 --tries=5
