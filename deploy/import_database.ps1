[System.Reflection.Assembly]::LoadFrom("$HOME\ssh_net\lib\net462\Renci.SshNet.dll") | Out-Null

$hostIP = "173.212.197.126"
$user = "root"
$pass = "aBh4HperXWe2923hW3r1d6d3JOTl"
$localSqlFile = "C:\xampp\htdocs\ettaba\ettabashop_db.sql"

if (-not (Test-Path $localSqlFile)) {
    throw "SQL file not found at: $localSqlFile"
}

Write-Host "--> Connecting via SCP to upload database dump (ettabashop_db.sql)..." -ForegroundColor Cyan
$scp = New-Object Renci.SshNet.ScpClient($hostIP, $user, $pass)
$scp.ConnectionInfo.Timeout = [System.TimeSpan]::FromMinutes(5)
$scp.Connect()

$fileInfo = [System.IO.FileInfo]$localSqlFile
Write-Host "Uploading $([math]::Round($fileInfo.Length / 1KB, 2)) KB to VPS /tmp/ettabashop_db.sql..." -ForegroundColor Cyan
$scp.Upload($fileInfo, "/tmp/ettabashop_db.sql")
$scp.Disconnect()
$scp.Dispose()
Write-Host "Upload completed successfully!" -ForegroundColor Green

Write-Host "--> Connecting via SSH to import database into MySQL..." -ForegroundColor Cyan
$ssh = New-Object Renci.SshNet.SshClient($hostIP, $user, $pass)
$ssh.ConnectionInfo.Timeout = [System.TimeSpan]::FromMinutes(10)
$ssh.Connect()

$importScript = @'
set -e
echo "Recreating clean ettaba_shop database..."
mysql -u root -e "
DROP DATABASE IF EXISTS ettaba_shop;
CREATE DATABASE ettaba_shop CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
GRANT ALL PRIVILEGES ON ettaba_shop.* TO 'ettaba_user'@'localhost';
GRANT ALL PRIVILEGES ON ettaba_shop.* TO 'ettaba_user'@'127.0.0.1';
FLUSH PRIVILEGES;
"

echo "Importing SQL dump into ettaba_shop database with FOREIGN_KEY_CHECKS=0..."
mysql -u root ettaba_shop -e "SET FOREIGN_KEY_CHECKS=0; SOURCE /tmp/ettabashop_db.sql; SET FOREIGN_KEY_CHECKS=1;"
rm -f /tmp/ettabashop_db.sql
echo "Database imported successfully!"

echo "Running migrations for any schema delta..."
cd /var/www/ettaba/ettabashop-admin/src
php artisan migrate --force

echo "Refreshing caches..."
php artisan optimize:clear
php artisan config:cache
php artisan route:cache
php artisan view:cache

cd /var/www/ettaba/ettabashop-website/src
php artisan optimize:clear
php artisan config:cache
php artisan route:cache
php artisan view:cache

echo "Fixing permissions..."
chown -R www-data:www-data /var/www/ettaba
chmod -R 775 /var/www/ettaba/ettabashop-admin/src/storage /var/www/ettaba/ettabashop-admin/src/bootstrap/cache
chmod -R 775 /var/www/ettaba/ettabashop-website/src/storage /var/www/ettaba/ettabashop-website/src/bootstrap/cache

echo "Reloading PHP-FPM..."
systemctl reload php8.2-fpm

echo "=== DATABASE SUMMARY ==="
mysql -u root ettaba_shop -e "
SELECT 'Users Count' AS Item, COUNT(*) AS Total FROM users
UNION ALL
SELECT 'Products Count', COUNT(*) FROM products
UNION ALL
SELECT 'Orders Count', COUNT(*) FROM orders
UNION ALL
SELECT 'Categories Count', COUNT(*) FROM categories;
"
'@

$cmd = $ssh.CreateCommand($importScript)
$cmd.CommandTimeout = [System.TimeSpan]::FromMinutes(10)
$result = $cmd.Execute()

Write-Output $result
if ($cmd.Error) {
    Write-Host "STDERR: $($cmd.Error)" -ForegroundColor Yellow
}

$ssh.Disconnect()
$ssh.Dispose()
