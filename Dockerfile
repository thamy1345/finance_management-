# syntax=docker/dockerfile:1
#
# Per-project Dockerfile. This is the ONLY file that should change project
# to project — everything reusable (system deps, PHP extensions, Apache
# config, php.ini) lives in the base image below.

##########################################
# Stage 1: PHP dependencies via Composer
##########################################
FROM composer:2 AS vendor

WORKDIR /app
COPY database/ database/
COPY composer.json composer.lock ./
RUN composer install \
    --no-dev --no-interaction --no-scripts --no-progress \
    --prefer-dist --optimize-autoloader --no-autoloader
COPY . .
RUN composer dump-autoload --optimize --classmap-authoritative

##########################################
# Stage 2: Frontend assets via Node/Vite
##########################################
# If this project uses Laravel Mix instead of Vite, change `npm run build`
# output path below (usually still public/build, but check webpack.mix.js).
# If there's no frontend bundler at all, delete this stage and the
# corresponding COPY --from=frontend line below.
FROM node:20-alpine AS frontend

WORKDIR /app
COPY package.json package-lock.json* ./
RUN npm ci
COPY . .
COPY --from=vendor /app/vendor ./vendor
RUN npm run build

##########################################
# Stage 3: Runtime — extends the shared base image
##########################################
# CHANGE THIS to your actual registry/tag once you've built and pushed
# the base image (see base/Dockerfile).
FROM laravel-base:8.4 AS runtime

# --- Project-specific extras go here (uncomment / add as needed) ---
# RUN docker-php-ext-install pdo_pgsql
# RUN pecl install imagick && docker-php-ext-enable imagick

WORKDIR /var/www/html

COPY --chown=www-data:www-data . .
COPY --from=vendor --chown=www-data:www-data /app/vendor ./vendor
COPY --from=frontend --chown=www-data:www-data /app/public/build ./public/build

RUN mkdir -p storage/framework/{cache,sessions,views} storage/logs bootstrap/cache \
    && chown -R www-data:www-data storage bootstrap/cache \
    && chmod -R 775 storage bootstrap/cache

EXPOSE 80
