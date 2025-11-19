# Stage 1: build frontend assets using Node
FROM node:18-alpine AS node-builder
WORKDIR /app
COPY package.json package-lock.json ./
RUN npm ci --silent
COPY resources resources
COPY vite.config.js .
RUN npm run build

# Stage 2: PHP runtime image
FROM php:8.1-fpm

# Install system dependencies
RUN apt-get update && apt-get install -y --no-install-recommends \
    git \
    unzip \
    libzip-dev \
    libpng-dev \
    libonig-dev \
    libicu-dev \
    zlib1g-dev \
    libxml2-dev \
 && docker-php-ext-install pdo_mysql mbstring zip exif pcntl bcmath intl gd \
 && rm -rf /var/lib/apt/lists/*

# Copy composer binary from official Composer image
COPY --from=composer:2 /usr/bin/composer /usr/local/bin/composer

WORKDIR /var/www/html

# Install PHP dependencies (cache composer files for layer caching)
COPY composer.json composer.lock ./
RUN composer install --no-dev --optimize-autoloader --no-interaction --prefer-dist --no-progress

# Copy application code
COPY . .

# Copy built frontend assets from node-builder
# Adjust path if your build outputs elsewhere (e.g., public/build)
COPY --from=node-builder /app/public/build public/build

# Ensure storage & cache directories are writable
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

EXPOSE 9000

# Run PHP-FPM in foreground
CMD ["php-fpm", "-F"]
