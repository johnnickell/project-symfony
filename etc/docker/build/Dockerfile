# Git 2.56.1 fixes the November 2026 Git security advisories. Debian's older
# packages are still affected; build the verified upstream release on the same
# base as the runtime so its HTTPS helper uses compatible shared libraries.
FROM php:8.5.12-cli-bookworm@sha256:d4f6e10eb393de7e3a7a88e8b88e139072dd3ae297f6a5dada8257e6fe32c585 AS git-build
RUN apt-get update \
    && apt-get install --no-install-recommends --yes \
        build-essential ca-certificates curl libcurl4-openssl-dev libexpat1-dev \
        libpcre2-dev libssl-dev perl xz-utils zlib1g-dev \
    && curl --fail --silent --show-error --location \
        https://www.kernel.org/pub/software/scm/git/git-2.56.1.tar.xz --output /tmp/git.tar.xz \
    && echo 'f0b0bf89264b9bd2e1c640363e7529643989da76281600473c50745cd34a607f  /tmp/git.tar.xz' | sha256sum --check --strict \
    && tar --extract --xz --file /tmp/git.tar.xz --directory /tmp \
    && make -C /tmp/git-2.56.1 -j2 prefix=/usr/local \
        NO_RUST=YesPlease NO_GETTEXT=YesPlease NO_TCLTK=YesPlease USE_LIBPCRE2=YesPlease all \
    && make -C /tmp/git-2.56.1 prefix=/usr/local \
        NO_RUST=YesPlease NO_GETTEXT=YesPlease NO_TCLTK=YesPlease USE_LIBPCRE2=YesPlease \
        DESTDIR=/opt/git install

FROM php:8.5.12-cli-bookworm@sha256:d4f6e10eb393de7e3a7a88e8b88e139072dd3ae297f6a5dada8257e6fe32c585

COPY --from=git-build /opt/git/usr/local/ /usr/local/

RUN php -r 'if (PHP_VERSION !== "8.5.12") { fwrite(STDERR, "Expected patched PHP 8.5.12; refusing this base image\n"); exit(1); }'

RUN apt-get update \
    && apt-get install --no-install-recommends --yes ca-certificates curl libexpat1 libpcre2-8-0 openssh-client perl zlib1g unzip \
    && pecl install xdebug \
    && docker-php-ext-enable xdebug \
    && rm -rf /var/lib/apt/lists/*

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

ENV XDEBUG_MODE=coverage

WORKDIR /app

# Preserve the distro executable path without retaining its affected Git package.
# A package that reintroduces /usr/bin/git makes this layer fail closed.
RUN ln -s /usr/local/bin/git /usr/bin/git \
    && test "$(git --version)" = 'git version 2.56.1' \
    && test "$(/usr/bin/git --version)" = 'git version 2.56.1' \
    && test "$(readlink /usr/bin/git)" = /usr/local/bin/git \
    && test -x "$(git --exec-path)/git-remote-https"
