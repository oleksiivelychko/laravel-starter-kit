FROM php:8.2-fpm

LABEL maintainer="Oleksii Velychko"

ARG PHP_IDE_CONFIG_SERVER_NAME
ARG CACHE_DIR="/tmp/cache"

ENV USER_ID 1000
ENV GROUP_ID 1000
ENV PHP_IDE_CONFIG="serverName=${PHP_IDE_CONFIG_SERVER_NAME}"
ENV COMPOSER_HOME=${CACHE_DIR}/composer
ENV npm_config_cache=${CACHE_DIR}/npm
ENV NO_UPDATE_NOTIFIER=1
ENV PHP_CS_FIXER_IGNORE_ENV=1
ENV LOG_DIR="/var/www/.docker/log"

COPY .docker/php/xdebug.ini /usr/local/etc/php/conf.d/xdebug.ini

RUN echo 'root:root' | chpasswd

WORKDIR /var/www

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
    librabbitmq-dev

RUN docker-php-ext-configure gd --with-freetype=/usr/include/ --with-jpeg=/usr/include/
RUN docker-php-ext-configure intl

RUN docker-php-ext-install \
    iconv \
    gd \
    intl \
    mbstring \
    mysqli \
    pdo \
    pdo_mysql \
    xsl \
    zip \
    soap \
    bcmath \
    bz2 \
    opcache \
    exif \
    sockets

RUN pecl install xdebug-3.2.2
RUN docker-php-ext-enable xdebug

RUN pecl install -o -f redis && rm -rf /tmp/pear && docker-php-ext-enable redis

RUN docker-php-source extract && \
    mkdir /usr/src/php/ext/amqp && \
    curl -L https://github.com/php-amqp/php-amqp/archive/master.tar.gz | tar -xzC /usr/src/php/ext/amqp --strip-components=1 && \
    docker-php-ext-install amqp && \
    docker-php-source delete

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

RUN curl -sL https://deb.nodesource.com/setup_20.x | bash -
RUN apt-get install -y nodejs
RUN npm i -g npm-check-updates
RUN npm config set fetch-retry-mintimeout 20000
RUN npm config set fetch-retry-maxtimeout 120000

RUN apt-get clean && rm -rf /var/lib/apt/lists/*

RUN mkdir -p ${CACHE_DIR}
RUN chown -R ${USER_ID}:${GROUP_ID} ${CACHE_DIR}

RUN usermod -u ${USER_ID} www-data && groupmod -g ${GROUP_ID} www-data
USER "${USER_ID}:${GROUP_ID}"

CMD ["php-fpm"]
