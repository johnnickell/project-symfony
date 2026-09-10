FROM php:8.5-fpm

WORKDIR /app

RUN apt-get update \
    && apt-get install --no-install-recommends --yes git unzip libicu-dev libzip-dev \
    && docker-php-ext-configure intl \
    && docker-php-ext-install intl zip \
    && rm -rf /var/lib/apt/lists/*

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

RUN groupadd --gid 1000 systemuser \
    && useradd --uid 1000 --create-home --shell /bin/bash --gid systemuser systemuser \
    && git config --system --add safe.directory /app

COPY ./entrypoint.sh /entrypoint.sh
COPY ./www.conf /usr/local/etc/php-fpm.d/www.conf

RUN chmod +x /entrypoint.sh

USER systemuser

EXPOSE 9000

ENTRYPOINT ["/entrypoint.sh"]
