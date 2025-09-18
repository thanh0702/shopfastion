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
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install pdo pdo_mysql gd

# Cài Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /var/www/html

# Copy toàn bộ source vào container
COPY . .

# Cài dependencies Laravel
RUN composer install --no-dev --optimize-autoloader

# Expose port Render yêu cầu
EXPOSE 10000

# Start Laravel
CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=10000"]


