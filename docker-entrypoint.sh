#!/bin/sh
set -e

# Clear cache để đảm bảo không còn config lỗi
php artisan config:clear
php artisan route:clear
php artisan view:clear

# Chạy lệnh CMD
exec "$@"
