heroku-git:
	heroku git:remote -a oleksiivelychkolaravelboard

composer-install:
	rm -rf vendor && rm -f composer.lock && docker-compose exec laravel-app composer install

npm-install:
	rm -rf node_modules && rm -f package-lock.json && docker-compose exec laravel-app npm i

npm-update:
	docker-compose exec laravel-app ncu -u

ide-helper:
	docker-compose exec laravel-app php artisan ide-helper:generate && docker-compose exec laravel-app php artisan ide-helper:meta && docker-compose exec laravel-app php artisan ide-helper:models -N

clear-cache:
	docker-compose exec laravel-app php artisan cache:clear && docker-compose exec laravel-app php artisan config:clear && docker-compose exec laravel-app php artisan route:clear
