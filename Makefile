artisan-app-generate:
	docker-compose exec laravel-app php artisan key:generate --ansi

artisan-migrate-refresh:
	docker-compose exec laravel-app php artisan migrate:refresh --force

artisan-db-seed:
	docker-compose exec laravel-app php artisan db:seed --force

artisan-storage-link:
	docker-compose exec laravel-app php artisan storage:link

artisan-test:
	docker-compose exec laravel-app php artisan test

composer-install:
	docker-compose exec laravel-app rm -rf vendor
	docker-compose exec laravel-app rm -f composer.lock
	docker-compose exec laravel-app composer install

git-push:
	git push heroku main

heroku-bash:
	heroku run bash -a oleksiivelychkolaravelboard

heroku-git:
	$(warning Must be logged before: `heroku login`)
	heroku git:remote -a oleksiivelychkolaravelboard

heroku-logs:
	heroku logs -n 200 --tail -a oleksiivelychkolaravelboard

heroku-packs:
	heroku buildpacks -a oleksiivelychkolaravelboard

ide-helper:
	docker-compose exec laravel-app php artisan ide-helper:generate
	docker-compose exec laravel-app php artisan ide-helper:meta
	docker-compose exec laravel-app php artisan ide-helper:models -N

npm-dev:
	docker-compose exec laravel-app npm run dev

npm-install:
	rm -rf node_modules
	rm -f package-lock.json
	docker-compose exec laravel-app npm i

npm-update:
	docker-compose exec laravel-app ncu -u

npm-watch:
	docker-compose exec laravel-app npm run watch

optimize-dev:
	docker-compose exec laravel-app sh /var/www/.docker/shell/optimize-dev.sh

php-test:
	docker-compose exec laravel-app ./vendor/bin/phpunit
