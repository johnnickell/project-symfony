FROM php:8.5.12-fpm-bookworm@sha256:d3db5340c7ecfb1e3a26af5d3d9de8b4e160cdd75692a830b5cb74b0b85db2cf

RUN php -r 'if (PHP_VERSION !== "8.5.12") { fwrite(STDERR, "Expected patched PHP 8.5.12; refusing this base image\n"); exit(1); }'

WORKDIR /app

ENV XDEBUG_MODE=coverage \
    COMPOSER_CACHE_DIR=/app/var/cache/composer \
    COMPOSER_ROOT_VERSION=dev-develop

RUN apt-get update \
    && apt-get install --no-install-recommends --yes git unzip libicu-dev libzip-dev \
    && docker-php-ext-configure intl \
    && docker-php-ext-install intl zip \
    && pecl install xdebug \
    && docker-php-ext-enable xdebug \
    && rm -rf /var/lib/apt/lists/*

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

ARG APP_UID=1000
ARG APP_GID=1000

RUN test "$APP_UID" -gt 0 && test "$APP_GID" -gt 0 \
    && groupadd --gid "$APP_GID" systemuser \
    && useradd --uid "$APP_UID" --create-home --shell /bin/bash --gid systemuser systemuser \
    && git config --system --add safe.directory /app

# Git checkouts may use a restrictive host umask; systemuser must read these files.
COPY --chmod=755 ./entrypoint.sh /entrypoint.sh
COPY --chmod=644 ./www.conf /usr/local/etc/php-fpm.d/www.conf
COPY --chmod=644 ./local.ini /usr/local/etc/php/conf.d/local.ini

USER systemuser

EXPOSE 9000

ENTRYPOINT ["/entrypoint.sh"]
