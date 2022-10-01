dockerexec := docker-compose exec laravel-app
dockerexecapp := $(dockerexec) php artisan

artisan-key-generate:
	$(dockerexecapp) key:generate --ansi

artisan-migrate-refresh:
	$(dockerexecapp) migrate:refresh --force

artisan-db-seed:
	$(dockerexecapp) db:seed --force

artisan-storage-link:
	$(dockerexecapp) storage:link

artisan-test:
	$(dockerexecapp) test

composer-install:
	$(dockerexec) rm -rf vendor
	$(dockerexec) rm -f composer.lock
	$(dockerexec) composer install

create-project:
	composer create-project laravel/laravel laravel-dashboard

ide-helper:
	$(dockerexecapp) ide-helper:generate
	$(dockerexecapp) ide-helper:meta
	$(dockerexecapp) ide-helper:models -N

npm-dev:
	$(dockerexec) npm run dev

npm-install:
	$(dockerexec) rm -rf node_modules
	$(dockerexec) rm -f package-lock.json
	$(dockerexec) npm i

npm-update:
	$(dockerexec) ncu -u

npm-watch:
	$(dockerexec) npm run watch

optimize-dev:
	$(dockerexec) sh /var/www/.docker/shell/optimize-dev.sh

phpunit:
	$(dockerexec) ./vendor/bin/phpunit
