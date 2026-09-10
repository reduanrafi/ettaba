# Implementation Plan: Direct Selling Panel

This document outlines the step-by-step technical implementation for the new **Direct Selling Panel** feature. Based on the previous architectural analysis, we will use a **Modular Isolation** strategy within the current monolith to prevent any disruption to the existing eCommerce and Merchant logic.

## User Review Required

> [!IMPORTANT]
> **Database Isolation:** We are proposing completely separate tables for Direct Seller Products and Orders. This is crucial because Direct Seller products have no images and different fields, and their order logic (instant commission) fundamentally differs from standard orders.

> [!WARNING]
> **Balance Tracking:** Since Direct Sellers need to "Add Money" and deduct from an e-Balance, we will add a dedicated `direct_selling_balance` to the `users` table rather than mixing it with other balances.

## Open Questions

> [!CAUTION]
> 1. In the Registration form, should the "Direct Selling" account require a referral code just like the "Partner Account", or is it optional?
> 2. Will the Direct Sellers use the exact same payment gateways (e.g., EPS, manual bank transfer) as the Merchants for the "Add Money" feature?

---

## Proposed Changes

We will divide the implementation into 3 logical components/phases.

### Component 1: Database & Registration (Phase 1)
We will create the core database structure and update the registration flow.

#### [MODIFY] `ettabashop-website/src/resources/views/auth/register.blade.php`
- Add a new `<option value="direct_selling">Direct Selling Account</option>` to the Account Type dropdown.
- Add JS logic to handle the visibility of conditional fields (like referral codes) if needed for this new type.

#### [MODIFY] `ettabashop-website/src/app/Http/Controllers/Auth/RegisterController.php`
- Update validation and creation logic to handle `customer_type = 'direct_selling'`.
- Ensure appropriate defaults (like `type = 'customer'` and `customer_type = 'direct_selling'`) are assigned properly.

#### [NEW] Migration: `create_direct_seller_products_table.php`
- Fields: `id`, `user_id` (seller), `name_bn`, `name_en`, `company_rate`, `seller_rate`, `erp`, `refer_commission`, `qty`, `tcb`, `reward_points`, `vat`, `timestamps`.

#### [NEW] Migration: `add_direct_selling_balance_to_users_table.php`
- Add `decimal('direct_selling_balance', 10, 2)->default(0)` to the `users` table.

#### [NEW] Migration: `create_direct_sales_orders_table.php`
- For recording instant-commission sales.

---

### Component 2: Isolated Dashboard & Panel (Phase 2)
We will build the dedicated panel for the Direct Seller without touching the Merchant views.

#### [NEW] Route File: `ettabashop-admin/src/routes/direct_seller.php`
- Register this in `RouteServiceProvider` to prefix all routes with `/direct-seller`.
- Apply a custom middleware (e.g., `role:direct_seller`).

#### [NEW] `App\Http\Controllers\DirectSeller\...`
- `DashboardController`: Handles fetching e-Balance, Today's Customers, Today's Sales, Today's Net Profit, etc.
- `ProductController`: Handles adding/editing direct seller products (automatically calculating TCB, Reward Points, VAT based on 25 Tk = 1 Point).

#### [NEW] `resources/views/direct-seller/...`
- Create blade layouts for `dashboard.blade.php`, `products/index.blade.php`, `products/create.blade.php`, etc.

---

### Component 3: The Core Sales Engine (Phase 3)
Handling the "Sales Confirm" button and instant commission distribution.

#### [NEW] `App\Services\DirectSellingOrderService.php`
- Create a dedicated service for processing a direct sale.
- Workflow:
  1. Verify the seller has sufficient `direct_selling_balance` >= total ERP.
  2. Deduct balance from seller.
  3. Create order in `direct_sales_orders`.
  4. **Instantly dispatch** commissions, cashback, and points to the customer and upline without touching the "pending funds" table.

---

## Verification Plan

### Automated Tests
- Run `php artisan migrate` to ensure all new tables are created without conflicts.
- Manually run registration flow using different Account Types to ensure validation rules hold.

### Manual Verification
- Register a test user as "Direct Selling Account".
- Log into the new panel (`/direct-seller/dashboard`).
- Add a product (verify TCB and points calculate correctly based on the formula).
- "Add Money" (simulate a balance load).
- Execute a "Sales Confirm" and verify that (a) e-Balance is deducted, and (b) commissions are distributed instantly.
