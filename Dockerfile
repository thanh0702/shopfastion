# Chọn base image PHP CLI 8.2
FROM php:8.2-cli

# Cài dependencies hệ thống
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
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install pdo pdo_mysql gd mbstring bcmath exif pcntl

# Cài MongoDB extension
RUN pecl install mongodb \
    && docker-php-ext-enable mongodb

# Cài Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

# Copy composer files trước để cache dependency
COPY composer.json composer.lock ./

# Cài packages Laravel (không cài dev để giảm kích thước)
RUN composer install --no-dev --optimize-autoloader --no-scripts

# Copy toàn bộ source code
COPY . .

# Chạy post-autoload và cache Laravel
RUN composer run-script post-autoload-dump || true
RUN php artisan config:cache && php artisan route:cache && php artisan view:cache

# Expose port để Render dùng
EXPOSE 10000

# Command để chạy Laravel
CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=10000"]
