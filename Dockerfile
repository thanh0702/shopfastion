# Dockerfile cho Laravel trên Render

FROM php:8.2-cli

# Đặt môi trường production trước
ENV APP_ENV=production
ENV APP_DEBUG=false

# Cài đặt các extensions cần thiết
RUN apt-get update && apt-get install -y \
    libpng-dev libjpeg-dev libfreetype6-dev zip git unzip curl libonig-dev libxml2-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install pdo pdo_mysql gd mbstring bcmath exif pcntl

# Cài MongoDB extension
RUN pecl install mongodb-2.1.1 \
    && docker-php-ext-enable mongodb

# Cài Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Set thư mục làm việc
WORKDIR /var/www/html

# Copy composer trước để cache dependency
COPY composer.json composer.lock ./
RUN composer install --no-dev --optimize-autoloader --ignore-platform-req=ext-mongodb

# Copy toàn bộ source code
COPY . .

# Xóa cache Laravel (đảm bảo không load Pail)
RUN php artisan config:clear \
 && php artisan route:clear \
 && php artisan view:clear

# Expose port Laravel
EXPOSE 10000

# Tạo entrypoint để xử lý các command trước khi serve
COPY docker-entrypoint.sh /usr/local/bin/docker-entrypoint.sh
RUN chmod +x /usr/local/bin/docker-entrypoint.sh
ENTRYPOINT ["docker-entrypoint.sh"]

# CMD chạy Laravel, Render sẽ gán PORT môi trường
CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=${PORT}"]
