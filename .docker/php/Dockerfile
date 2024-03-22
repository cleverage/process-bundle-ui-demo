ARG PHP_VERSION=8.2
FROM php:${PHP_VERSION}-fpm AS php
ARG XDEBUG_VERSION=3.1.4
ARG DOCKER_HTTP_PORT=8000
ENV DOCKER_HTTP_PORT=${DOCKER_HTTP_PORT}

# Set user and group
ARG UID=1000
ARG GID=1000
RUN usermod --uid ${UID} www-data && \
    groupmod --gid ${GID} www-data

ADD https://github.com/mlocati/docker-php-extension-installer/releases/latest/download/install-php-extensions /usr/local/bin/
RUN chmod +x /usr/local/bin/install-php-extensions
RUN curl -1sLf 'https://dl.cloudsmith.io/public/symfony/stable/setup.deb.sh' | bash -E

# Install packages
RUN apt-get update && apt-get install -y \
    wget \
    git \
    unzip \
    libicu-dev \
    libzip-dev \
    gosu \
    symfony-cli \
    supervisor \
    procps \
    && docker-php-ext-configure intl \
    && install-php-extensions \
        intl \
        pcntl \
        opcache \
        zip \
        gd \
        imagick \
        pdo \
        pdo_pgsql \
        pgsql \
        pdo_mysql \
        mysqli \
        sysvsem
RUN pecl install xdebug-${XDEBUG_VERSION}
## Install composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

COPY entrypoint.sh /usr/local/bin/entrypoint.sh
RUN chmod +x /usr/local/bin/entrypoint.sh
ENTRYPOINT ["entrypoint.sh"]
CMD service supervisor start;symfony server:stop --all;symfony server:ca:install;symfony serve --port=${DOCKER_HTTP_PORT};
