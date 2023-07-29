### Configure project.

📌 Use [script](https://raw.githubusercontent.com/oleksiivelychko/go-queue-service/main/.ops/scripts/generate-certs.sh) to generate wildcard certificate:
```
CERTS_DIR=${PWD}/.docker/certs ./generate-certs.sh laravel-starter-kit.local mail.laravel-starter-kit.local mq.laravel-starter-kit.local
```

📌 Generate new application key:
```
php artisan key:generate --ansi
```
