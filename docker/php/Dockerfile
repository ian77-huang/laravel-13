FROM php:8.4-cli

RUN apt-get update \
    && apt-get install -y --no-install-recommends \
        git \
        unzip \
        zip \
        curl \
        procps \
        libmemcached-dev \
        libssl-dev \
        libz-dev \
        libbrotli-dev \
    && docker-php-ext-install -j"$(nproc)" pcntl pdo_mysql sockets \
    && docker-php-ext-enable opcache \
    && pecl install swoole memcached \
    && docker-php-ext-enable swoole memcached \
    && apt-get install -y --no-install-recommends libmemcached11t64 \
    && apt-get purge -y --auto-remove libmemcached-dev libssl-dev libz-dev libbrotli-dev \
    && pecl clear-cache \
    && rm -rf /var/lib/apt/lists/* /tmp/pear

COPY docker/php/opcache.dev.ini /usr/local/etc/php/conf.d/opcache.ini

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

RUN curl -fsSL https://deb.nodesource.com/setup_22.x | bash - \
    && apt-get install -y --no-install-recommends nodejs \
    && rm -rf /var/lib/apt/lists/*

WORKDIR /var/www/html
