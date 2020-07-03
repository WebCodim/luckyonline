FROM php:7.2-fpm

RUN apt-get update && apt-get install -y \
    default-mysql-client \
    && docker-php-ext-install -j$(nproc) iconv mbstring mysqli pdo_mysql

VOLUME /app

WORKDIR /app
