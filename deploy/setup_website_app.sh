#!/bin/bash
set -e

echo "--> Configuring ettabashop-website..."
cd /var/www/ettaba/ettabashop-website/src

if [ ! -f .env ]; then
    cp .env.example .env
fi

sed -i 's|^APP_NAME=.*|APP_NAME="Ettaba Shop"|' .env
sed -i 's|^APP_ENV=.*|APP_ENV=production|' .env
sed -i 's|^APP_DEBUG=.*|APP_DEBUG=false|' .env
sed -i 's|^APP_URL=.*|APP_URL=http://173.212.197.126|' .env
sed -i 's|^DB_HOST=.*|DB_HOST=127.0.0.1|' .env
sed -i 's|^DB_DATABASE=.*|DB_DATABASE=ettaba_shop|' .env
sed -i 's|^DB_USERNAME=.*|DB_USERNAME=ettaba_user|' .env
sed -i 's|^DB_PASSWORD=.*|DB_PASSWORD=Ettaba@SecurePass2026!|' .env

composer install --no-dev --no-interaction --prefer-dist --optimize-autoloader
php artisan key:generate --force
php artisan storage:link || true
php artisan optimize:clear
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Set permissions
chown -R www-data:www-data /var/www/ettaba
chmod -R 775 /var/www/ettaba/ettabashop-website/src/storage /var/www/ettaba/ettabashop-website/src/bootstrap/cache

echo "--> Website setup completed successfully!"
