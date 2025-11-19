FROM php:8.1-fpm

# Install system dependencies
RUN apt-get update && apt-get install -y \
    git \
    unzip \
    curl \
    libzip-dev \
    libpng-dev \
    libonig-dev \
    libicu-dev \
    zlib1g-dev \
    libxml2-dev \
    nodejs \
    npm \
 && docker-php-ext-install pdo_mysql mbstring zip exif pcntl bcmath intl gd \
 && rm -rf /var/lib/apt/lists/*

# Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Set working directory
WORKDIR /var/www/html

# Copy composer files first for dependency install caching
COPY composer.json composer.lock ./

RUN composer install --no-dev --optimize-autoloader --no-interaction || true

# Copy node files and build assets if present
COPY package.json package-lock.json ./
RUN if [ -f package.json ]; then npm install && npm run build; fi || true

# Copy application code
COPY . .

# Ensure storage & cache directories are writable
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache || true

EXPOSE 8080

# Default command: use $PORT if provided by platform
CMD ["sh", "-lc", "php artisan serve --host=0.0.0.0 --port=${PORT:-8080}"]
