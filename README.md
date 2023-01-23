# laravel-starter-kit

### Dashboard management application based on PHP8/Laravel8. No JetStream. No Liveware. No Tailwind. Completely dockerized and ideal to start into local development.

📌 Use test credentials to get access to dashboard:
```
email: admin@test.test
password: secret
```
![Dashboard management UI](social_preview.png)
![Swagger OpenAPI](storage/screenshots/swagger_openapi.png)

📌 Generate wildcard certificate:
```
openssl req -x509 -days 365 -out .docker/certs/localhost.crt -keyout .docker/certs/localhost.key \
      -newkey rsa:2048 -nodes -sha256 \
      -subj '/CN=laravel-starter-kit.local' -extensions EXT -config <( \
       printf "[dn]\nCN=laravel-starter-kit.local\n[req]\ndistinguished_name = dn\n[EXT]\nsubjectAltName=DNS:laravel-starter-kit.local,DNS:mail.laravel-starter-kit.local\nkeyUsage=digitalSignature\nextendedKeyUsage=serverAuth")

echo 127.0.0.1 laravel-starter-kit.local mail.laravel-starter-kit.local >> /etc/hosts
```

📌 Add certificate to `Keychain Access`:
![Keychain Access: Trust Certificate](storage/screenshots/keychain_access_trust_certificate.png)

📌 Set up debug process in PhpStorm:
![PHPStorm: Debug Server](storage/screenshots/phpstorm_debug_server.png)
![PHPStorm: Template Debug](storage/screenshots/phpstorm_template_debug.png)
![PHPStorm: Validate Debug Configuration](storage/screenshots/phpstorm_validate_debug_configuration.png)

The sample images were taken from <a href="unsplash.com">unsplash.com</a> and <a href="icons8.com">icons8.com</a>
