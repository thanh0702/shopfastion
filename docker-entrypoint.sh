#!/bin/sh
set -e

# Clear cache trước khi start Laravel
php artisan config:clear
php artisan route:clear
php artisan view:clear

# Nếu có migration cần chạy
# php artisan migrate --force

exec "$@"
