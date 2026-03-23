FROM php:8.2-fpm

# 1. Install System Dependencies (Added Node.js and NPM)
RUN apt-get update && apt-get install -y \
    git curl libpng-dev libonig-dev libxml2-dev zip unzip nginx libpq-dev \
    nodejs npm

# 2. Install PHP Extensions
RUN docker-php-ext-install pdo_pgsql mbstring exif pcntl bcmath gd

# 3. Get Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www
COPY . .

# 4. THE BUILD PHASE (This is what makes your design live)
RUN composer install --no-dev --optimize-autoloader
RUN npm install
RUN npm run build

# 5. Permissions
RUN chown -R www-data:www-data /var/www/storage /var/www/bootstrap/cache
RUN chmod -R 775 /var/www/storage /var/www/bootstrap/cache

EXPOSE 10000

# 6. Start Command
CMD php artisan migrate --force && php artisan serve --host 0.0.0.0 --port 10000
