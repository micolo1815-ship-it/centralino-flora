FROM php:8.2-fpm

# Install system dependencies
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip \
    nginx \
    libpq-dev  # Added this for PostgreSQL system support

# Install PHP extensions
# Added pdo_pgsql to the list below
RUN docker-php-ext-install pdo_mysql pdo_pgsql mbstring exif pcntl bcmath gd

# Get latest Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /var/www

# Copy project files
COPY . .

# Install dependencies
RUN composer install --no-dev --optimize-autoloader

# Setup Nginx and Permissions
RUN chown -R www-data:www-data /var/www/storage /var/www/bootstrap/cache
RUN chmod -R 775 /var/www/storage /var/www/bootstrap/cache

# Expose port and start
# Note: Render Free Tier expects port 10000, so we match EXPOSE to your CMD port
EXPOSE 10000
# This will drop all tables and re-run every migration perfectly for Postgres
CMD php artisan migrate:fresh --force && php artisan serve --host 0.0.0.0 --port 10000
