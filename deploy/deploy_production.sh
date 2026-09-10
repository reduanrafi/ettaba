#!/bin/bash
set -e

echo "=========================================="
echo " Starting Ettaba Deployment on VPS        "
echo " Timestamp: $(date)                       "
echo "=========================================="

PROJECT_DIR="/var/www/ettaba"

if [ ! -d "$PROJECT_DIR" ]; then
    echo "Error: Project directory $PROJECT_DIR does not exist."
    exit 1
fi

cd "$PROJECT_DIR"

echo "--> Pulling latest changes from repository..."
git fetch origin main
git reset --hard origin/main

echo "--> Deploying Admin Application..."
if [ -d "$PROJECT_DIR/ettabashop-admin/src" ]; then
    cd "$PROJECT_DIR/ettabashop-admin/src"
    
    # Check if .env exists
    if [ ! -f .env ]; then
        echo "Warning: .env file missing in ettabashop-admin/src!"
    fi

    composer install --no-dev --no-interaction --prefer-dist --optimize-autoloader
    php artisan migrate --force
    php artisan optimize:clear
    php artisan config:cache
    php artisan route:cache
    php artisan view:cache
    php artisan storage:link || true
fi

echo "--> Deploying Website Application..."
if [ -d "$PROJECT_DIR/ettabashop-website/src" ]; then
    cd "$PROJECT_DIR/ettabashop-website/src"

    # Check if .env exists
    if [ ! -f .env ]; then
        echo "Warning: .env file missing in ettabashop-website/src!"
    fi

    composer install --no-dev --no-interaction --prefer-dist --optimize-autoloader
    php artisan optimize:clear
    php artisan config:cache
    php artisan route:cache
    php artisan view:cache
    php artisan storage:link || true
fi

echo "--> Setting storage & cache permissions..."
chown -R www-data:www-data "$PROJECT_DIR"
chmod -R 775 "$PROJECT_DIR/ettabashop-admin/src/storage" "$PROJECT_DIR/ettabashop-admin/src/bootstrap/cache" 2>/dev/null || true
chmod -R 775 "$PROJECT_DIR/ettabashop-website/src/storage" "$PROJECT_DIR/ettabashop-website/src/bootstrap/cache" 2>/dev/null || true

echo "--> Reloading PHP 8.2 FPM & Nginx..."
systemctl reload php8.2-fpm
systemctl reload nginx

echo "=========================================="
echo " Deployment Completed Successfully!       "
echo "=========================================="
