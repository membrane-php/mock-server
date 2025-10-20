FROM php:8.3-fpm-alpine

RUN apk update && apk add nginx
RUN docker-php-ext-install pdo pdo_mysql

WORKDIR /app

COPY docker/nginx.conf /etc/nginx/sites-available/default

# Installing dependencies before copying source code
# increases the likelihood of utilizing the cache.
COPY --from=composer/composer:latest-bin /composer /usr/bin/composer
COPY composer.* /app/
RUN /usr/bin/composer install --no-dev --no-interaction

COPY . /app

RUN bin/setup

EXPOSE 8080
EXPOSE 8081

ENTRYPOINT ["/app/docker/entrypoint.sh"]
