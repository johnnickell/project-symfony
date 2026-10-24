FROM php:8.5.12-cli-bookworm@sha256:d4f6e10eb393de7e3a7a88e8b88e139072dd3ae297f6a5dada8257e6fe32c585

RUN php -r 'if (PHP_VERSION !== "8.5.12") { fwrite(STDERR, "Expected patched PHP 8.5.12; refusing this base image\n"); exit(1); }'

RUN apt-get update \
    && apt-get install --no-install-recommends --yes git unzip \
    && pecl install xdebug \
    && docker-php-ext-enable xdebug \
    && rm -rf /var/lib/apt/lists/*

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

ENV XDEBUG_MODE=coverage

WORKDIR /app
