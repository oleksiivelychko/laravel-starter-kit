# Stage 1 - Download PHP dependencies.
FROM composer:2.5 as vendor

COPY composer.json composer.json
COPY composer.lock composer.lock

RUN composer install \
    --ignore-platform-reqs \
    --no-dev \
    --no-interaction \
    --no-plugins \
    --no-scripts \
    --prefer-dist \
    --optimize-autoloader \
    --quiet

# Stage 2 - download NPM dependencies and build assets.
FROM node:18-alpine as public

WORKDIR /app
RUN mkdir -p /app/public

COPY package.json package-lock.json webpack.mix.js /app/
COPY resources/js/ /app/resources/js/
COPY resources/css/ /app/resources/css/
COPY storage/app/public/images/ /app/storage/app/public/images/
COPY public/* /app/public

RUN npm i --quiet
RUN npm run prod > '/dev/null' 2>&1

# Stage 3 - build PHP extensions/modules and run application.
FROM php:8.2-fpm as build

ENV DB_CONNECTION pgsql

RUN apt-get -qq update && apt-get -qq install -y \
    curl \
    libfreetype6-dev \
    libicu-dev \
    libonig-dev \
    libjpeg62-turbo-dev \
    libpng-dev \
    libxslt1-dev \
    libxml2-dev \
    zlib1g-dev \
    libbz2-dev \
    libzip-dev \
    librabbitmq-dev \
    libpq-dev \
    nginx

RUN docker-php-ext-configure gd --with-freetype=/usr/include/ --with-jpeg=/usr/include/
RUN docker-php-ext-configure intl

RUN docker-php-ext-install \
    iconv \
    gd \
    intl \
    mbstring \
    xsl \
    zip \
    soap \
    bcmath \
    bz2 \
    opcache \
    exif \
    sockets \
    pdo \
    pdo_pgsql

RUN pecl install -o -f redis && rm -rf /tmp/pear && docker-php-ext-enable redis

RUN docker-php-source extract && \
    mkdir /usr/src/php/ext/amqp && \
    curl -L https://github.com/php-amqp/php-amqp/archive/master.tar.gz | tar -xzC /usr/src/php/ext/amqp --strip-components=1 && \
    docker-php-ext-install amqp && \
    docker-php-source delete

RUN apt-get clean && rm -rf /var/lib/apt/lists/*

COPY ./.docker/php/production/php.ini /usr/local/etc/php
COPY ./.docker/php/production/entrypoint.sh /etc/entrypoint.sh
COPY ./.docker/nginx/virtualhost.production.conf /etc/nginx/sites-enabled/default

WORKDIR /var/www
COPY . .

COPY --from=vendor /app/vendor/ /var/www/vendor/
COPY --from=public /app/public/ /var/www/public/

RUN rm -rf /var/www/html && \
    rm /var/www/bootstrap/cache/*.php && \
    mkdir -p /var/www/log

RUN usermod -u 1000 www-data && groupmod -g 1000 www-data

RUN chown -R "1000:1000" /var/www
RUN chown -R www-data:www-data /var/log/nginx
RUN chmod -R 755 /var/log/nginx

RUN php -r 'file_exists(".env") || copy(".env.example", ".env");'
RUN chmod +x /etc/entrypoint.sh

RUN php artisan config:cache && \
    php artisan route:cache && \
    php artisan view:cache

USER "root:root"

EXPOSE 80 443

ENTRYPOINT ["/etc/entrypoint.sh"]
