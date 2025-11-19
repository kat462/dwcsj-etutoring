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
# Copy composer files and minimal app folders Composer may scan so autoload
# generation doesn't fail (e.g. database/seeders). This preserves cache
# benefits while ensuring required paths exist at this layer.
COPY composer.json composer.lock ./
COPY app/ app/
COPY database/ database/
COPY routes/ routes/
COPY config/ config/
COPY bootstrap/ bootstrap/
COPY resources/ resources/
RUN composer install --no-dev --optimize-autoloader --no-interaction --prefer-dist --no-progress

# Copy remaining application code
COPY . .

# Copy built frontend assets from node-builder
# Adjust path if your build outputs elsewhere (e.g., public/build)
COPY --from=node-builder /app/public/build public/build

# Ensure storage & cache directories are writable
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

# Expose the HTTP port we will serve on (default Railway $PORT)
EXPOSE 5000

# Run PHP built-in web server using shell expansion for $PORT. Using
# sh -c ensures the environment variable is expanded at runtime.
CMD ["sh", "-c", "n=0; until php artisan migrate --force >/dev/stdout 2>&1; do n=$((n+1)); if [ $n -ge 10 ]; then echo 'migration attempts exhausted'; break; fi; echo \"migrate attempt $n failed, sleeping...\"; sleep 3; done; php artisan config:clear || true; php -S 0.0.0.0:${PORT:-5000} -t public"]
