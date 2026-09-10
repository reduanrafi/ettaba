---
name: ettaba-navigator
description: Guides navigation, system map, database schema, and debugging workflow for the Ettaba Shop workspace.
---

# Ettaba Shop System Navigator Skill

Use this skill to navigate the dual-project Laravel setup, map database tables, troubleshoot payment flows, and execute commands efficiently in this workspace.

---

## 1. System Map & Core Directories

This workspace consists of two Laravel instances inside a bootstrapping directory layout, running under a local XAMPP environment:

```
c:\xampp\htdocs\ettaba
├── ettabashop-admin/              # Admin Panel and Merchant backend wrapper
│   └── src/                       # Laravel Admin source directory
│       ├── app/
│       │   ├── Http/Controllers/  # Controllers (Admin, Auth, HandCash, etc.)
│       │   ├── Models/            # Database Eloquent models (60+ models)
│       │   └── Services/          # Service layer (e.g. EpsPaymentService)
│       ├── config/                # Laravel Configuration
│       ├── database/
│       │   └── migrations/        # Database migrations (62 migration files)
│       ├── resources/views/       # Blade templates and user interface assets
│       ├── routes/                # Route definitions (web.php, api.php)
│       └── storage/logs/          # Laravel application logs
└── ettabashop-website/            # User-facing website/frontend wrapper
    └── src/                       # Laravel Website source directory
```

---

## 2. Database Schema Guide

Both apps connect to the same MySQL database (`ettaba_shop`). The tables fall into these primary functional categories:

### Users & Organization
- `users`: Standard user login credentials, type, status, and `virtual_balance`.
- `profiles`: Customer and merchant profiles (first name, last name, phone, address, etc.).
- `shops`: Vendor/shop configuration records.
- `trainings` & `user_trainings`: User educational courses/training logs.
- `blocked_users`: Blocked accounts tracker.
- `merchant_referrals`: Merchant referral link mappings.

### Products & Inventory
- `categories`: General categories (includes `advance_payment_required` flag).
- `brands`: Product brands.
- `products`: Product catalogs (includes pricing, details, and `direct_refer_commission`).
- `product_images` & `product_reviews`: Images and reviews.
- `sliders`: Home screen carousel media assets.

### Order Processing
- **Standard Orders**: `orders`, `order_items`, `order_statuses`.
- **Anonymous Orders**: `anonymous_orders`, `anonymous_order_items`.
- **Direct Orders**: `direct_orders` (direct purchase bypass).
- **Hand Cash Orders**: `hand_cash_orders`, `hand_cash_order_items`. (Hand Cash products/categories are in `hand_cash_products`, `hand_cash_categories`).

### Payment Gateways & Balance Adding
- `merchant_gateways`: Gateway configurations (`eps`, `mfs`, `card`, `amex`).
- `merchant_balance_add_histories`: Transactions for merchant balance additions (stores amount, charges, status, and payment reference).

### Earnings, Points & Fund Distribution
- **Earnings & points**: `earnings`, `earning_histories`, `points`, `point_histories`, `pending_points`.
- **Multi-Level Funds**: 15+ tables managing fund allocation:
  - `company_main_funds`, `company_pending_funds`, `user_pending_funds`.
  - Time-based: `daily_funds`, `weekly_funds`, `monthly_funds`.
  - Target-based: `intensive_funds`, `executive_funds`, `vendor_funds`, `local_funds`, `up_funds`, `district_funds`, `division_funds`, `marketing_funds`.
  - `company_fallback_amounts`.
- **Withdrawals**: `withdraw_requests`, `withdraws`, `withdraw_histories`.

---

## 3. Core Business Workflows

### EPS Payment Gateway Flow
Managed by [EpsPaymentService](file:///c:/xampp/htdocs/ettaba/ettabashop-admin/src/app/Services/EpsPaymentService.php) and [MerchantAddMoneyController](file:///c:/xampp/htdocs/ettaba/ettabashop-admin/src/app/Http/Controllers/HandCash/MerchantAddMoneyController.php):

1. **Authentication**: `getToken()` makes a POST request to `/v1/Auth/GetToken` using the environment username, password, and header `x-hash` generated using HMAC-SHA512 of the username and Hash Key.
2. **Session Initialization**:
   - `initializePayment()` (for standard orders) / `initializeMerchantPayment()` (for balance adding).
   - Generates a unique transaction ID.
   - Makes a POST request to `/v1/EPSEngine/InitializeEPS` with details, customer data, and success/fail/cancel redirect URLs.
   - Returns a `RedirectURL` pointing to the payment gateway gateway portal.
3. **Verification & Callback**:
   - Success URL: `paymentSuccess($transaction_id)`. Verifies the status with `checkTransactionStatus($merchantTransactionId, $epsTransactionId)`.
   - If verified, increments the user's `virtual_balance` or marks the order as paid.
   - Failure/Cancel URLs: `paymentFail()` / `paymentCancel()`. Updates transaction status to `failed` or `cancelled`.

---

## 4. Troubleshooting Recipes

1. **Payment Failures**:
   - Check if EPS environment configurations (`EPS_URL`, `EPS_STORE_ID`, `EPS_MERCHANT_ID`, `EPS_HASH_KEY`) in the `.env` files are correct and match the sandbox/production keys.
   - Verify transaction logs in `c:\xampp\htdocs\ettaba\ettabashop-admin\src\storage\logs\laravel.log`.
2. **Database Connection Issues**:
   - Both apps share connection configuration: `DB_DATABASE=ettaba_shop` using default XAMPP credentials (`username: root`, `password: ` empty).
   - If a table or column is missing, run migrations from the appropriate admin/website root `src` directory:
     ```powershell
     cd c:\xampp\htdocs\ettaba\ettabashop-admin\src
     php artisan migrate
     ```
3. **Cache Synchronization**:
   - After adding routes, editing config parameters, or updating Blade views, execute these commands inside the `src` directory to clear old state:
     ```powershell
     php artisan optimize:clear
     ```
