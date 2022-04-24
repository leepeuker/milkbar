# https://hub.docker.com/_/php
FROM php:8.1.4-fpm-alpine3.15

ARG USER_ID

# system update
RUN apk update && apk upgrade

# install system dependencies
RUN apk add --no-cache git                  \
                       bash                 \
                       nano                 \
                       curl                 \
                       wget                 \
                       unzip                \
                       make                 \
                       zip                  \
                       libzip-dev           \
                       $PHPIZE_DEPS


# install php dependencies
RUN docker-php-ext-install mysqli       \
                           pdo          \
                           pdo_mysql    \
                           zip

# install composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer --version=2.3.3

RUN addgroup --gid "$USER_ID" "dev" \
    && adduser \
    --disabled-password \
    --gecos "" \
    --ingroup "dev" \
    --uid "$USER_ID" \
    "dev"

WORKDIR /var/www
