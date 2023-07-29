### Configure project.

📌 Use [script](https://raw.githubusercontent.com/oleksiivelychko/go-queue-service/main/.ops/scripts/generate-certs.sh) to generate wildcard certificate:
```
CERTS_DIR=${PWD}/storage/certs ./generate-certs.sh laravel-starter-kit.local mail.laravel-starter-kit.local mq.laravel-starter-kit.local
```

📌 Generate new application key:
```
php artisan key:generate --ansi
```

📌 Make a request to invoke payment hook:
```
curl -X POST -H "Content-Type: application/json" -d '{"order_id":1,"status":"IN_PROGRESS"}' https://laravel-starter-kit.local/hooks/payment
```
