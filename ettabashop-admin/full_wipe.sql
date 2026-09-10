-- ============================================================
--  ETTABA DATABASE — FULL WIPE SCRIPT
--  ⚠️  BACKUP FIRST:
--      mysqldump -u root ettaba_db > backup_before_clean.sql
-- ============================================================

SET FOREIGN_KEY_CHECKS = 0;

-- ------------------------------------------------------------
-- 1. FINANCIAL / COMMISSION TABLES
-- ------------------------------------------------------------
TRUNCATE TABLE user_pending_funds;
TRUNCATE TABLE company_pending_funds;
TRUNCATE TABLE company_main_funds;
TRUNCATE TABLE company_fallback_amounts;
TRUNCATE TABLE daily_funds;
TRUNCATE TABLE weekly_funds;
TRUNCATE TABLE monthly_funds;
TRUNCATE TABLE intensive_funds;
TRUNCATE TABLE executive_funds;
TRUNCATE TABLE vendor_funds;
TRUNCATE TABLE local_funds;
TRUNCATE TABLE up_funds;
TRUNCATE TABLE district_funds;
TRUNCATE TABLE division_funds;
TRUNCATE TABLE marketing_funds;

-- ------------------------------------------------------------
-- 2. POINTS & EARNINGS
-- ------------------------------------------------------------
TRUNCATE TABLE pending_points;
TRUNCATE TABLE point_histories;
TRUNCATE TABLE points;
TRUNCATE TABLE earning_histories;
TRUNCATE TABLE earnings;

-- ------------------------------------------------------------
-- 3. WITHDRAWALS
-- ------------------------------------------------------------
TRUNCATE TABLE withdraw_histories;
TRUNCATE TABLE withdraws;
TRUNCATE TABLE withdraw_requests;

-- ------------------------------------------------------------
-- 4. ORDERS  (children before parents)
-- ------------------------------------------------------------
TRUNCATE TABLE order_items;
TRUNCATE TABLE order_statuses;
TRUNCATE TABLE orders;

TRUNCATE TABLE hand_cash_order_items;
TRUNCATE TABLE hand_cash_orders;

TRUNCATE TABLE direct_orders;

TRUNCATE TABLE anonymous_order_items;
TRUNCATE TABLE anonymous_orders;

-- ------------------------------------------------------------
-- 5. USER PROFILE & RELATED DATA
-- ------------------------------------------------------------
TRUNCATE TABLE user_generation_groups;
TRUNCATE TABLE user_trainings;
TRUNCATE TABLE blocked_users;
TRUNCATE TABLE product_reviews;
TRUNCATE TABLE addresses;
TRUNCATE TABLE profiles;
TRUNCATE TABLE shops;
TRUNCATE TABLE search_keywords;

-- ------------------------------------------------------------
-- 6. AUTH / SESSIONS
-- ------------------------------------------------------------
TRUNCATE TABLE personal_access_tokens;
TRUNCATE TABLE password_resets;
TRUNCATE TABLE failed_jobs;

-- ------------------------------------------------------------
-- 7. USERS  — keep the admin account(s)
-- ------------------------------------------------------------
DELETE FROM users WHERE type != 'admin';

-- Reset auto-increment so new IDs start from 1
ALTER TABLE users AUTO_INCREMENT = 1;

-- ============================================================
-- ✅ MASTER DATA IS PRESERVED:
--    divisions, districts, upazilas, payment_methods,
--    categories, brands, products, product_images,
--    sliders, top_products, top_types, trainings,
--    hand_cash_categories, hand_cash_products
-- ============================================================

SET FOREIGN_KEY_CHECKS = 1;

-- Run these in Laravel after the wipe:
-- php artisan cache:clear
-- php artisan config:clear
-- php artisan view:clear
