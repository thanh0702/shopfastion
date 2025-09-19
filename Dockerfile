# --------------------------
# Dockerfile PHP 8.2 + Apache + MongoDB + Composer
# --------------------------

# 1. Chọn image PHP 8.2 với Apache
FROM php:8.2-apache

# 2. Cài đặt các dependency cần thiết để build MongoDB extension
RUN apt-get update && apt-get install -y --no-install-recommends \
    libssl-dev \
    libcurl4-openssl-dev \
    zip unzip git \
    build-essential \
    && a2enmod rewrite \
    && rm -rf /var/lib/apt/lists/*

# 2.1. Cài đặt MongoDB extension cho PHP 8.2
RUN pecl install mongodb && docker-php-ext-enable mongodb

# 3. Cài Composer từ image chính thức
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# 4. Thiết lập thư mục làm việc
WORKDIR /var/www/html

# 5. Copy toàn bộ source code vào container
COPY . /var/www/html

# 5.1. Thay đổi DocumentRoot của Apache thành /var/www/html/public
RUN sed -i 's|/var/www/html|/var/www/html/public|g' /etc/apache2/sites-available/000-default.conf

# 5.2. Recreate the public/storage symlink
RUN rm -rf public/storage && ln -s ../storage/app/public public/storage

# 6. Cài đặt các dependency PHP của Laravel
RUN composer install --no-dev --optimize-autoloader --no-scripts --ignore-platform-reqs

# 7. Cấp quyền cho Laravel storage và cache
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

# 8. Mở cổng 80
EXPOSE 80

# 9. Chạy Apache ở foreground
CMD ["apache2-foreground"]
