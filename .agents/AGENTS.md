# Workspace Rules: Ettaba Shop

This directory contains two Laravel applications wrapped under a bootstrapping root structure:
1. **Admin Panel**: `ettabashop-admin` (Source code in [ettabashop-admin/src](file:///c:/xampp/htdocs/ettaba/ettabashop-admin/src))
2. **Website/Front-end**: `ettabashop-website` (Source code in [ettabashop-website/src](file:///c:/xampp/htdocs/ettaba/ettabashop-website/src))

Both systems connect to the same MySQL database (`ettaba_shop`).

---

## 1. Running Commands & Terminal Operations

> [!IMPORTANT]
> The root directory (`c:\xampp\htdocs\ettaba`) does not contain an `artisan` file. All Laravel operations must be executed from the corresponding nested `src/` directory.

- **Admin Commands Cwd**: Run commands with current working directory set to `c:\xampp\htdocs\ettaba\ettabashop-admin\src`.
- **Website Commands Cwd**: Run commands with current working directory set to `c:\xampp\htdocs\ettaba\ettabashop-website\src`.

### Standard Recipes
- **Run Migrations**: `php artisan migrate`
- **Rollback Migrations**: `php artisan migrate:rollback`
- **Run Seeder**: `php artisan db:seed`
- **Clear Caches**:
  ```bash
  php artisan cache:clear
  php artisan config:clear
  php artisan route:clear
  php artisan view:clear
  ```
- **Tinker Session**: `php artisan tinker`

---

## 2. Coding Conventions & Codebase Structure

- **Controllers**: Located in `app/Http/Controllers/`. Standard controllers are structured inside namespace-based folders:
  - Admin operations: `App\Http\Controllers\Admin`
  - Auth: `App\Http\Controllers\Auth`
  - Hand Cash / Merchant Balance Add operations: `App\Http\Controllers\HandCash`
- **Models**: Located in `app/Models/`. Models share the same database tables. Keep schema documentation up to date.
- **Services**: Business logic (e.g. payment gateway integrations like EPS) should be isolated in `app/Services/` (e.g., `App\Services\EpsPaymentService`).
- **Views**: Written using Blade templates, located in `resources/views/`. Use layout extending and components.
- **Routing**: Group routes logically in `routes/web.php` or `routes/api.php` under appropriate prefixes and middleware (e.g., `admin`, `merchant`, `auth`).

---

## 3. Debugging Checklist

When diagnosing bugs, check the following in order:
1. **Laravel Logs**: Inspect the error log file at:
   - Admin: [laravel.log](file:///c:/xampp/htdocs/ettaba/ettabashop-admin/src/storage/logs/laravel.log)
   - Website: [laravel.log](file:///c:/xampp/htdocs/ettaba/ettabashop-website/src/storage/logs/laravel.log)
2. **Database State**: Verify the schema matches the latest migrations in `database/migrations/`.
3. **Environment (.env)**: Verify variables such as payment credentials (`EPS_URL`, `EPS_STORE_ID`, etc.) and database credentials.
4. **Session/Cache**: Clear Laravel cache if routes, config, or views do not reflect edits.
