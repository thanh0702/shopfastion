FROM php:8.2-cli

# Cài đặt dependencies và PHP extensions
RUN apt-get update && apt-get install -y \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    zip \
    git \
    unzip \
    curl \
    libonig-dev \
    libxml2-dev \
    pkg-config \
    libssl-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install pdo pdo_mysql gd mbstring bcmath exif pcntl

# Cài MongoDB extension
RUN pecl install mongodb \
    && docker-php-ext-enable mongodb

# Cài Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

# Copy composer trước để cache dependency
COPY composer.json composer.lock ./
RUN composer install --no-dev --optimize-autoloader --no-scripts

# Copy toàn bộ code
COPY . .

# Build cache cho Laravel
RUN php artisan config:cache && php artisan route:cache && php artisan view:cache || true

# Expose port Render yêu cầu
EXPOSE 10000

# Start Laravel
CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=10000"]
