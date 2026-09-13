#!/bin/bash
set -e

echo "=========================================="
echo " Initializing Ettaba Project on VPS       "
echo "=========================================="

mkdir -p /var/www/ettaba
cd /var/www/ettaba

# Clone or pull repo
if [ ! -d "/var/www/ettaba/.git" ]; then
    echo "--> Cloning repository from GitHub..."
    git clone https://github.com/reduanrafi/ettaba.git /var/www/ettaba
else
    echo "--> Pulling latest changes..."
    git fetch origin main
    git reset --hard origin/main
fi

# ----------------- ADMIN SETUP -----------------
echo "--> Setting up Admin Application..."
cd /var/www/ettaba/ettabashop-admin/src

if [ ! -f .env ]; then
    echo "Creating admin .env..."
    cp .env.example .env || true
fi

# Set admin environment variables
sed -i "s|^APP_NAME=.*|APP_NAME=\"Ettaba Admin\"|" .env
sed -i "s|^APP_ENV=.*|APP_ENV=production|" .env
sed -i "s|^APP_DEBUG=.*|APP_DEBUG=false|" .env
sed -i "s|^APP_URL=.*|APP_URL=http://173.212.197.126:8080|" .env
sed -i "s|^DB_HOST=.*|DB_HOST=127.0.0.1|" .env
sed -i "s|^DB_DATABASE=.*|DB_DATABASE=ettaba_shop|" .env
sed -i "s|^DB_USERNAME=.*|DB_USERNAME=ettaba_user|" .env
sed -i "s|^DB_PASSWORD=.*|DB_PASSWORD=Ettaba@SecurePass2026!|" .env

composer install --no-dev --no-interaction --prefer-dist --optimize-autoloader
php artisan key:generate --force
php artisan storage:link || true
php artisan migrate --force
php artisan optimize:clear
php artisan config:cache
php artisan route:cache
php artisan view:cache

# ----------------- WEBSITE SETUP -----------------
echo "--> Setting up Website Application..."
cd /var/www/ettaba/ettabashop-website/src

if [ ! -f .env ]; then
    echo "Creating website .env..."
    cp .env.example .env || true
fi

# Set website environment variables
sed -i "s|^APP_NAME=.*|APP_NAME=\"Ettaba Shop\"|" .env
sed -i "s|^APP_ENV=.*|APP_ENV=production|" .env
sed -i "s|^APP_DEBUG=.*|APP_DEBUG=false|" .env
sed -i "s|^APP_URL=.*|APP_URL=http://173.212.197.126|" .env
sed -i "s|^DB_HOST=.*|DB_HOST=127.0.0.1|" .env
sed -i "s|^DB_DATABASE=.*|DB_DATABASE=ettaba_shop|" .env
sed -i "s|^DB_USERNAME=.*|DB_USERNAME=ettaba_user|" .env
sed -i "s|^DB_PASSWORD=.*|DB_PASSWORD=Ettaba@SecurePass2026!|" .env

composer install --no-dev --no-interaction --prefer-dist --optimize-autoloader
php artisan key:generate --force
php artisan storage:link || true
php artisan optimize:clear
php artisan config:cache
php artisan route:cache
php artisan view:cache

# ----------------- PERMISSIONS & SERVICES -----------------
echo "--> Applying file permissions..."
chown -R www-data:www-data /var/www/ettaba
chmod -R 775 /var/www/ettaba/ettabashop-admin/src/storage /var/www/ettaba/ettabashop-admin/src/bootstrap/cache
chmod -R 775 /var/www/ettaba/ettabashop-website/src/storage /var/www/ettaba/ettabashop-website/src/bootstrap/cache

echo "--> Reloading Web Server & PHP-FPM..."
systemctl reload php8.2-fpm
systemctl reload nginx

echo "=========================================="
echo " Ettaba Project Initialized Successfully! "
echo "=========================================="
