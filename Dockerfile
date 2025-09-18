FROM php:8.2-cli

# Cài đặt extensions cần thiết
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

# Cài Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Cho phép composer chạy với root
ENV COMPOSER_ALLOW_SUPERUSER=1

WORKDIR /var/www/html

# Copy composer trước để cache dependency
COPY composer.json composer.lock ./
RUN composer install --no-dev --optimize-autoloader

# Copy toàn bộ source code
COPY . .

# Expose port Render yêu cầu
EXPOSE 10000

# Start Laravel (package:discover sẽ tự chạy khi install)
CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=10000"]
