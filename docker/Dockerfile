FROM php:8.3-fpm-alpine

RUN apk update && apk add nginx
COPY docker/nginx.conf /etc/nginx/sites-available/default

RUN docker-php-ext-install pdo pdo_mysql # use the mlocati php-extension-installer to get pdo and sqlite3

COPY --from=composer/composer:latest-bin /composer /usr/bin/composer

COPY . /app
WORKDIR /app

RUN /usr/bin/composer install --no-dev --no-interaction

RUN vendor/bin/generate-hydrators src/ generated/api/ \
    --hydrator_namespace_prefix="Membrane\\MockServer\\Generated\\Api\\Hydrator" \
    --psr4_namespace_prefix="Membrane\\MockServer\\Generated\\Api"
RUN vendor/bin/generate-repositories src/ generated/api/ \
    --repositories_namespace_prefix="Membrane\\MockServer\\Generated\\Api\\Repository" \
    --psr4_namespace_prefix="Membrane\\MockServer\\Generated\\Api"

EXPOSE 8080
EXPOSE 8081

ENTRYPOINT ["/app/docker/entrypoint.sh"]
