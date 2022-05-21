FROM php:8.1-fpm

LABEL maintainer="Oleksii Velychko"

ARG CACHE_DIR="/tmp/cache"
ARG WORK_DIR="/var/www"
ARG LOG_DIR="/var/www/log"

ENV ROOT_PASSWORD $ROOT_PASSWORD
ENV USER_ID 1000
ENV GROUP_ID 1000
ENV COMPOSER_HOME=${CACHE_DIR}/composer
ENV npm_config_cache=${CACHE_DIR}/npm
ENV NO_UPDATE_NOTIFIER=1

RUN echo 'root:${DOCKER_PASSWORD}' | chpasswd

WORKDIR ${WORK_DIR}
RUN mkdir -p ${CACHE_DIR}
RUN mkdir -p ${LOG_DIR}

RUN apt-get update && apt-get install -y \
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
    libssh-dev \
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

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

RUN curl -sL https://deb.nodesource.com/setup_16.x | bash -
RUN apt-get install -y nodejs

RUN apt-get clean && rm -rf /var/lib/apt/lists/*
RUN rm -rf ${WORK_DIR}/html

COPY ./.docker/php/production/php.ini /usr/local/etc/php
COPY ./.docker/nginx/virtualhost.production.conf /etc/nginx/sites-enabled/default

RUN usermod -u ${USER_ID} www-data && groupmod -g ${GROUP_ID} www-data

COPY . ${WORK_DIR}

RUN chown -R "${USER_ID}:${GROUP_ID}" ${CACHE_DIR}
RUN chown -R "${USER_ID}:${GROUP_ID}" ${WORK_DIR}

USER "${USER_ID}:${GROUP_ID}"

RUN composer install --no-dev --ignore-platform-reqs
RUN npm i && npm run prod
RUN php -r 'file_exists(".env") || copy(".env.example", ".env");'

USER "root:root"

COPY ./.docker/php/production/entrypoint.sh /etc/entrypoint.sh
RUN chmod +x /etc/entrypoint.sh

EXPOSE 80 443

ENTRYPOINT ["/etc/entrypoint.sh"]
