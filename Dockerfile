# Use official PHP image with Apache
FROM php:8.2-apache

# Install system dependencies and PHP extensions
RUN apt-get update && apt-get install -y \
    libssl-dev \
    libcurl4-openssl-dev \
    zip unzip git \
    build-essential \
    && pecl install mongodb \
    && docker-php-ext-configure mongodb --with-mongodb-ssl=shared \
    && docker-php-ext-install mongodb \
    && docker-php-ext-enable mongodb \
    && a2enmod rewrite

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /var/www/html

# Copy existing application directory contents
COPY . /var/www/html

# Install PHP dependencies (after ensuring MongoDB extension is ready)
RUN composer install --no-dev --optimize-autoloader --no-scripts --ignore-platform-reqs

# Set permissions for Laravel storage and bootstrap cache
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

# Expose port 80
EXPOSE 80

# Start Apache in foreground
CMD ["apache2-foreground"]