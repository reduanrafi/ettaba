#!/bin/bash
set -e

export DEBIAN_FRONTEND=noninteractive

echo "=========================================="
echo " Starting Ettaba VPS Setup (Ubuntu 24.04) "
echo "=========================================="

# 1. Swap Configuration (2GB)
if [ ! -f /swapfile ]; then
    echo "--> Creating 2GB Swap file..."
    fallocate -l 2G /swapfile || dd if=/dev/zero of=/swapfile bs=1M count=2048
    chmod 600 /swapfile
    mkswap /swapfile
    swapon /swapfile
    echo '/swapfile none swap sw 0 0' >> /etc/fstab
    echo "Swap created successfully."
fi

# 2. System Update & Dependencies
echo "--> Updating system packages..."
apt-get update -y
apt-get install -y software-properties-common curl wget git unzip zip ufw acl supervisor gnupg2 ca-certificates lsb-release

# 3. Add PHP Repository (Ondrej PPA)
echo "--> Adding Ondrej PHP PPA repository..."
add-apt-repository -y ppa:ondrej/php
apt-get update -y

# 4. Install PHP 8.2 & Extensions
echo "--> Installing PHP 8.2 & Extensions..."
apt-get install -y \
    php8.2-fpm \
    php8.2-cli \
    php8.2-common \
    php8.2-mysql \
    php8.2-mbstring \
    php8.2-xml \
    php8.2-bcmath \
    php8.2-curl \
    php8.2-gd \
    php8.2-zip \
    php8.2-intl \
    php8.2-soap \
    php8.2-redis \
    php8.2-tokenizer

# Optimize PHP-FPM settings
sed -i "s/upload_max_filesize = .*/upload_max_filesize = 64M/" /etc/php/8.2/fpm/php.ini
sed -i "s/post_max_size = .*/post_max_size = 64M/" /etc/php/8.2/fpm/php.ini
sed -i "s/memory_limit = .*/memory_limit = 512M/" /etc/php/8.2/fpm/php.ini
sed -i "s/max_execution_time = .*/max_execution_time = 300/" /etc/php/8.2/fpm/php.ini

systemctl restart php8.2-fpm
systemctl enable php8.2-fpm

# 5. Install Composer
echo "--> Installing Composer..."
if ! command -v composer &> /dev/null; then
    curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
fi

# 6. Install & Configure MySQL Server
echo "--> Installing MySQL Server..."
apt-get install -y mysql-server
systemctl start mysql
systemctl enable mysql

# Create database and user
DB_NAME="ettaba_shop"
DB_USER="ettaba_user"
DB_PASS="Ettaba@SecurePass2026!"

echo "--> Configuring MySQL database and user..."
mysql -u root -e "
CREATE DATABASE IF NOT EXISTS \`${DB_NAME}\` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
CREATE USER IF NOT EXISTS '${DB_USER}'@'localhost' IDENTIFIED WITH caching_sha2_password BY '${DB_PASS}';
CREATE USER IF NOT EXISTS '${DB_USER}'@'127.0.0.1' IDENTIFIED WITH caching_sha2_password BY '${DB_PASS}';
GRANT ALL PRIVILEGES ON \`${DB_NAME}\`.* TO '${DB_USER}'@'localhost';
GRANT ALL PRIVILEGES ON \`${DB_NAME}\`.* TO '${DB_USER}'@'127.0.0.1';
FLUSH PRIVILEGES;
"

# 7. Install & Configure Nginx
echo "--> Installing Nginx..."
apt-get install -y nginx

# Adjust client_max_body_size in nginx.conf
if ! grep -q "client_max_body_size" /etc/nginx/nginx.conf; then
    sed -i '/http {/a \    client_max_body_size 64M;' /etc/nginx/nginx.conf
fi

# Create directory for project
mkdir -p /var/www/ettaba
chown -R www-data:www-data /var/www/ettaba
chmod -R 775 /var/www/ettaba

# Website Nginx Configuration (Port 80)
cat << 'EOF' > /etc/nginx/sites-available/ettabashop-website
server {
    listen 80 default_server;
    listen [::]:80 default_server;
    server_name _;

    root /var/www/ettaba/ettabashop-website/src/public;
    index index.php index.html;

    charset utf-8;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location = /favicon.ico { access_log off; log_not_found off; }
    location = /robots.txt  { access_log off; log_not_found off; }

    error_page 404 /index.php;

    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.2-fpm.sock;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
        fastcgi_read_timeout 300;
    }

    location ~ /\.(?!well-known).* {
        deny all;
    }
}
EOF

# Admin Panel Nginx Configuration (Port 8080)
cat << 'EOF' > /etc/nginx/sites-available/ettabashop-admin
server {
    listen 8080;
    listen [::]:8080;
    server_name _;

    root /var/www/ettaba/ettabashop-admin/src/public;
    index index.php index.html;

    charset utf-8;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location = /favicon.ico { access_log off; log_not_found off; }
    location = /robots.txt  { access_log off; log_not_found off; }

    error_page 404 /index.php;

    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.2-fpm.sock;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
        fastcgi_read_timeout 300;
    }

    location ~ /\.(?!well-known).* {
        deny all;
    }
}
EOF

# Enable Nginx sites
rm -f /etc/nginx/sites-enabled/default
ln -sf /etc/nginx/sites-available/ettabashop-website /etc/nginx/sites-enabled/ettabashop-website
ln -sf /etc/nginx/sites-available/ettabashop-admin /etc/nginx/sites-enabled/ettabashop-admin

nginx -t
systemctl restart nginx
systemctl enable nginx

# 8. Setup SSH Deploy Key for GitHub Actions
echo "--> Configuring SSH Deploy Key for CI/CD..."
mkdir -p /root/.ssh
chmod 700 /root/.ssh

if [ ! -f /root/.ssh/id_github_actions ]; then
    ssh-keygen -t ed25519 -N "" -f /root/.ssh/id_github_actions -C "github-actions-ettaba"
    cat /root/.ssh/id_github_actions.pub >> /root/.ssh/authorized_keys
    chmod 600 /root/.ssh/authorized_keys
fi

# 9. Configure Firewall (UFW)
echo "--> Configuring UFW Firewall..."
ufw --force reset
ufw default deny incoming
ufw default allow outgoing
ufw allow 22/tcp comment 'SSH'
ufw allow 80/tcp comment 'HTTP Website'
ufw allow 443/tcp comment 'HTTPS'
ufw allow 8080/tcp comment 'HTTP Admin'
ufw --force enable

echo "=========================================="
echo " VPS Setup Completed Successfully! "
echo "=========================================="
