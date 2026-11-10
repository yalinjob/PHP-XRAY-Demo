# Stage 1: resolve the (intentionally old) Composer dependencies.
FROM composer:2.2 AS vendor
WORKDIR /app
COPY composer.json composer.lock* ./
RUN composer install --no-dev --no-scripts --no-interaction --prefer-dist --no-progress --optimize-autoloader --ignore-platform-req=ext-fileinfo

# Stage 2: runtime. Older base image on purpose so Xray also reports OS-level findings.
FROM php:7.4-apache-bullseye
ENV APACHE_DOCUMENT_ROOT=/var/www/html/public
RUN sed -ri "s!/var/www/html!${APACHE_DOCUMENT_ROOT}!g" /etc/apache2/sites-available/*.conf /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

WORKDIR /var/www/html
COPY --from=vendor /app/vendor ./vendor
COPY --from=vendor /app/composer.json /app/composer.lock* ./
COPY public ./public
COPY src ./src

ARG APP_VERSION=dev
ENV APP_VERSION=${APP_VERSION}
EXPOSE 80
