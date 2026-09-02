FROM php:8.5-cli-bookworm

RUN apt-get update \
    && apt-get install --no-install-recommends --yes git unzip python3 \
    && rm -rf /var/lib/apt/lists/*

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /app
