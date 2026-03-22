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
    libpq-dev \
    gnupg # Added gnupg for secure Node.js installation

# --- NEW: Install Node.js for Bootstrap/JS Compilation ---
RUN curl -fsSL https://deb.nodesource.com/setup_20.x | bash - && \
    apt-get install -y nodejs

# Install PHP extensions
RUN docker-php-ext-install pdo_mysql pdo_pgsql mbstring exif pcntl bcmath gd

# Get latest Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /var/www

# Copy project files
COPY . .

# --- NEW: Build Bootstrap Assets ---
# This ensures your CSS/JS is compiled before the app goes live
RUN npm install
RUN npm run build

# Install PHP dependencies
RUN composer install --no-dev --optimize-autoloader

# Setup Nginx and Permissions
RUN chown -R www-data:www-data /var/www/storage /var/www/bootstrap/cache
RUN chmod -R 775 /var/www/storage /var/www/bootstrap/cache

# Expose port and start
EXPOSE 10000

# Using migrate --force instead of migrate:fresh to protect your data now that you're deployable
CMD php artisan migrate --force && php artisan serve --host 0.0.0.0 --port 10000
