# Ettaba Shop - VPS Setup & CI/CD Walkthrough

## Summary of Accomplishments

We have provisioned the VPS server (`173.212.197.126`), migrated and cached both Laravel applications, resolved duplicate route name collisions & migration constraints, and configured automated GitHub Actions CI/CD deployment.

---

## 1. VPS Server Details & Live Endpoints

| Service | Address / Port | Status | Details |
| :--- | :--- | :--- | :--- |
| **Website (Storefront)** | `http://173.212.197.126` (Port 80) | `HTTP 200 OK` | Root: `/var/www/ettaba/ettabashop-website` |
| **Admin Panel** | `http://173.212.197.126:8080` (Port 8080) | `HTTP 200 OK` | Root: `/var/www/ettaba/ettabashop-admin` |
| **PHP Runtime** | PHP 8.2.33 + PHP-FPM | Active | `/var/run/php/php8.2-fpm.sock` |
| **Database** | MySQL 8.0.46 | Active | DB: `ettaba_shop` (53 tables migrated) |
| **Firewall (UFW)** | Ports 22, 80, 443, 8080 | Active | Secure incoming traffic policy |

---

## 2. GitHub Actions CI/CD Configuration

The repository is synchronized at: [`reduanrafi/ettaba`](https://github.com/reduanrafi/ettaba.git) on branch `main`.

Workflow file: [deploy.yml](file:///c:/xampp/htdocs/ettaba/.github/workflows/deploy.yml)

### Step to Activate CI/CD on GitHub:
Go to your GitHub repository:
👉 **`https://github.com/reduanrafi/ettaba/settings/secrets/actions`**
Click **"New repository secret"** and add the following 3 secrets:

1. **`VPS_HOST`**:
   ```
   173.212.197.126
   ```

2. **`VPS_USERNAME`**:
   ```
   root
   ```

3. **`VPS_SSH_KEY`**:
   ```text
   -----BEGIN OPENSSH PRIVATE KEY-----
   b3BlbnNzaC1rZXktdjEAAAAABG5vbmUAAAAEbm9uZQAAAAAAAAABAAAAMwAAAAtzc2gtZW
   QyNTUxOQAAACABBSFPck3kCaLJHijrP80L3by4L2UnqJv4oSiXyiS/hwAAAJibPUhcmz1I
   XAAAAAtzc2gtZWQyNTUxOQAAACABBSFPck3kCaLJHijrP80L3by4L2UnqJv4oSiXyiS/hw
   AAAEDH2RLQKXzLWF4sSoCaM6xw6AB6qYqfAZvibPWnSPZIkAEFIU9yTeQJoskeKOs/zQvd
   vLgvZSeom/ihKJfKJL+HAAAAFWdpdGh1Yi1hY3Rpb25zLWV0dGFiYQ==
   -----END OPENSSH PRIVATE KEY-----
   ```

*(Optional: `VPS_PORT` defaults to `22` if not set).*

---

## 3. Automated Deployment Pipeline Flow

Whenever you `git push` to `main`, GitHub Actions will automatically:
1. Connect to VPS (`173.212.197.126`) over SSH.
2. Run `/var/www/ettaba/deploy/deploy_production.sh`.
3. Pull the latest code (`git reset --hard origin/main`).
4. Run `composer install --no-dev --optimize-autoloader`.
5. Run database migrations: `php artisan migrate --force`.
6. Clear and compile cached routes, config, and views for ultra-fast performance.
7. Correct directory permissions for `www-data`.
8. Reload PHP 8.2-FPM & Nginx with zero downtime.

---

## 4. Verification

- Ran full deployment script `/var/www/ettaba/deploy/deploy_production.sh` on the VPS.
- Verified `curl -sI http://127.0.0.1` -> `HTTP/1.1 200 OK` (Set-Cookie `laravel_session`).
- Verified `curl -sI http://127.0.0.1:8080` -> `HTTP/1.1 200 OK` (Set-Cookie `ettaba_admin_session`).
