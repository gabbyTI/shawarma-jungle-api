FROM php:8.0.5-fpm-alpine

RUN docker-php-ext-install pdo pdo_mysql