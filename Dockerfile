FROM php:8.0.5-fpm-alpine

RUN apk add icu-dev
RUN docker-php-ext-configure intl
RUN docker-php-ext-install intl
RUN docker-php-ext-enable intl

RUN docker-php-ext-install pdo pdo_mysql
