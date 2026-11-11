# Ettaba Shop — Weekly Requirements Task List (06 November 2026)

This document breaks down all requirements from the 18-page specification PDF into actionable, trackable tasks organized by module.

---

## Phase 1: Website Homepage & Reward Section (Page 1)
- [x] **1.1 Homepage Summary Table Alignment & Spelling Fix**:
  - Center-align the text labels and numeric values inside the 6-cell balance/points summary grid on the homepage (`ই-ব্যালেন্স`, `পেন্ডিং ব্যালেন্স`, `মোট পয়েন্ট`, `পেন্ডিং পয়েন্ট`, `মোট আয়`, `মোট উত্তোলন`).
  - Fix the spelling of **"ব্যালেন্স"** (currently misspelled in the UI).
  - Refine the card styling so it looks natural, clean, and less "AI-generated".
- [x] **1.2 Homepage Next Reward Card Redesign**:
  - Under `"আপনার পরবর্তী পুরস্কার"`, display `"পাবেন: [পুরস্কারের নাম]"` (e.g., `পাবেন: একটি আকর্ষণীয় মগ`) on a highlighted bar.
  - Change the first stat label from `"মোট পয়েন্ট"` to `"বর্তমান পয়েন্ট"` (or match the reference layout).
  - Update the third stat box (`"পরবর্তী পুরস্কার পাবেন"`) to show `"[X] পয়েন্ট-এ"` as shown in the reference screenshot.
  - Below the progress bar, add a distinct message box with a growth icon:
    - Line 1 (Bold): `"যতবেশি কেনাকাটা ও সার্ভিস নিবেন, তত বড় পুরস্কার পাবেন!"`
    - Line 2: `"তাই ইত্তেবা অ্যাপ বা ইত্তেবা লোকাল মার্চেন্ট থেকে নিয়মিত কেনাকাটা করুন ও সার্ভিস নিন!"`
- [x] **1.3 Admin Reward Application Accept / Reject Bug Fix**:
  - Fix the issue in the Admin Panel where reward applications (`পুরস্কার আবেদন`) cannot be accepted or rejected/cancelled (testable with demo account password `12345678900`).

---

## Phase 2: Online Seller Sidebar, Header & Dashboard (Pages 2–3)
- [x] **2.1 Seller Sidebar Profile & Menu Restructure (Page 2)**:
  - Display Seller Profile info at the top of the sidebar using the Shop Logo as the profile image:
    - Seller Name (e.g., `Demo Seller`)
    - Seller ID: `sID:15[id]` (prefixed with `15`)
    - Account Created Date: `D-M-YYYY`
  - Update Seller Sidebar menu items (English only, remove Bangla labels):
    1. `Dashboard`
    2. `My Orders` (remove `(অর্ডারসমূহ)`)
    3. `Add Product`
    4. `Hidden Product`
    5. `Shop Profile`
    6. `Add Money`
    7. `My Withdraws`
- [x] **2.2 Seller Top Header & Dashboard Updates (Page 3)**:
  - In the mobile/top navbar, replace `"Ettaba Admin"` with `"Ettaba Online Seller"` positioned inline between the hamburger menu icon and the logout icon.
  - Rename the dashboard heading from `"Online Seller Dashboard"` to `"Seller Dashboard"`.
  - Change the green order button text to `"My Orders"` (remove Bangla text), and show the `"X New"` badge right next to it on the Seller Homepage when new orders arrive.
  - **Order Cancellation Sync**: If Admin cancels an order from the Admin Panel, automatically mark it as cancelled/removed from the Seller's order action step as well.
  - **Seller Reward System & Card**:
    - Add a Seller Reward structure in the Admin Panel (matching Customer, Partner, and Merchant reward structures) and show theSeller's Reward List like Customer accounts.
    - Embed the Next Reward progress card on the Seller Dashboard with motivational text (`"যত বেশি পয়েন্ট ও বিক্রি, তত বড় পুরস্কার!"`).
    - Adjust Seller Dashboard metric card UI colors to harmonize with the reward card palette.

---

## Phase 3: Independent Account Status & Seller Shop Profile (Pages 4–5)
- [x] **3.1 Independent Customer vs. Seller/Merchant Registration & Blocking (Page 4)**:
  - Allow the same NID and Mobile Number to register and use both a **Customer Account** and a **Seller/Merchant Account** independently.
  - **Cross-Blocking Rules**:
    - Blocking a Seller or Merchant account blocks that NID & Mobile Number from creating or using any Seller or Merchant account (Seller block prevents Merchant registration and vice versa).
    - Blocking a Seller/Merchant account does **not** affect the Customer account.
    - Blocking a Customer account does **not** affect the Seller/Merchant account.
- [x] **3.2 Seller Shop Profile Form Overhaul (Pages 4–5)**:
  - Make all labels English-only and style inputs with a clean app-style UI. Mark all fields except Social Media links as required (`*`).
  - **Fields to include**:
    1. `Shop Name (English/Bangla) *` (single field replacing separate English/Bangla shop name fields; remove Shop Description fields).
    2. `Custom Shop URL (Slug) *` with live/button availability check (`URLটি খালি আছে কিনা, চেক করার অপশন`).
    3. `Seller NID/TIN Certificate No *` with placeholder: `"ব্যক্তি মালিকানা ব্যবসার ক্ষেত্রে NID এবং কোম্পানির ক্ষেত্রে TIN Certificate No ব্যবহার করুন"`.
    4. `Date of birth *` with placeholder: `"NID এর ক্ষেত্রে জন্ম তারিখ এবং TIN এর ক্ষেত্রে TIN তৈরি তারিখ উল্লেখ করুন"`.
    5. `Shop Address *` using cascading dropdowns: **Division → District → Upazila → Municipality/Union → Ward** (same as Customer Registration).
    6. `Delivery area: (Bangla/English) *` with placeholder: `"আপনার পণ্য কোথায় ডেলিভারি দেওয়া হয় স্পষ্ট করে লিখুন। যেমন- নির্দিষ্ট কোনো জেলা, উপজেলা, পৌরসভা বা ইউনিয়ন? নাকি সমগ্র বাংলাদেশ? সেটা লিখুন। একাধিক এলাকা লিখতে কমা (,) ব্যবহার করুন"` (keep the helper note below the box).
    7. `Mobile Number *`: English digits only (no letters, symbols, or Bangla digits), must start with `01` and be exactly 11 digits.
    8. **Social Media Links** (Optional): `Youtube channel link`, `Facebook page link`, `Instagram profile link` with helper note below: `"লোকাল অফিস/এডমিনের অনুমোদন সাপেক্ষে এই প্রোফাইল আপনার সেলার পেজে শো করবে"`.
    9. `Logo image *` only (remove `Banner image`).
  - **Public Seller Shop Page & Admin Approval for Social Links**:
    - Display the social media icons (YouTube, Facebook, Instagram) in the seller header card below `"এই সেলার ডেলিভারি দেয়"` only when approved/enabled by Admin/Local Office.
    - Allow Admin to approve/hide seller social links and edit any seller profile information at any time.

---

## Phase 4: Seller Product Page Enhancements (Pages 6–8)
- [x] **4.1 Seller-Specific Category Permissions (Page 6)**:
  - Allow Admin to assign one or more allowed product categories to each Seller.
  - On the Seller's Add/Edit Product page, only display the categories approved for that Seller.
- [x] **4.2 Remove Delivery Area & Fee from Product Page (Page 6)**:
  - Remove the `"Delivery Area & Delivery Fee"` section from the Seller Product form.
- [x] **4.3 Pricing, Seller Rate & Per-Seller ERP Margin % (Page 7)**:
  - Rename `"Seller Rate with VAT (৳)"` to `"Seller Rate"` and remove all VAT-related conditions/warnings (`15% VAT` minimum check).
  - Replace the fixed 10 Taka ERP difference rule with a **per-seller configurable percentage** controlled from the Admin Panel (e.g., 3%, 5%).
  - Dynamically display the ERP helper message:
    `"ERP অবশ্যই Seller Rate থেকে (X%) এর ব্যবধান হতে হবে (সর্বনিম্ন: Y টাকা)। কাস্টমারের সুবিধার্থে সেলার ইচ্ছে করলে, আরো বেশি ব্যবধান রাখতে পারেন।"`
  - Remove `"Seller Payable Amount"` and `"VAT (15% of ERP)"` from the Seller Product page summary.
- [x] **4.4 Mandatory Product Weight (Page 8)**:
  - Make `"Product Weight (gm/kg)"` a required (`*`) field on the Seller Product form.
- [x] **4.5 Optional Product Size & Color Variants (Page 8)**:
  - Place Size & Color options below Product Weight and above Featured Image:
    - **Size**: Optional custom values (allows Bangla/English letters, numbers, symbols, e.g., `100ml`, `200ml`, `S`, `M`, `L`, `2-3Y`).
    - **Color**: Optional custom values (allows only Bangla & English letters; no numbers or symbols, e.g., `Red`, `Green`, `লাল`, `সবুজ`).
  - **Frontend Behavior**:
    - Only show Size/Color selector buttons on the Product Details page if the seller added them.
    - If a product has Size or Color options and a customer clicks "Add to Cart" from the Homepage/listing, redirect them to the Product Details page first so they must select Size/Color before adding to cart.
- [x] **4.6 Automatic Product Image Compression (Page 8)**:
  - Automatically compress uploaded seller product images on the server/client while preserving resolution/aspect ratio so file sizes are reduced to roughly `100KB–300KB`.

---

## Phase 5: Online Seller Self-Delivery System & eBalance (Pages 9–12)
- [x] **5.1 Seller Delivery Type Classification (Page 9)**:
  - Support two delivery types for Online Sellers configurable by Admin:
    - **Option 1: Ettaba Delivery** (existing flow remains completely unchanged).
    - **Option 2: Self Delivery** (Seller manages order statuses, delivery charge, and delivery execution; company collects agreed commission/margin).
- [x] **5.2 Self-Delivery Order Management & Pending Due Tracking (Page 10)**:
  - Give Self-Delivery Sellers full order management controls (`Accept`, `Cancel`, `On Delivery`, `Delivered`, `Done`), ability to set custom delivery charge on the order details page, and ability to remove items belonging to other sellers (mirroring Admin Delivery Management).
  - **Pending Balance / Due Calculation**:
    - For Self-Delivery Sellers, when an order is placed, record the difference between `ERP` and `Seller Rate` (company commission/margin) as a **Pending Due** per order.
    - Display individual `Pending Due` per order and show the total as a negative value in the Seller's `Pending Balance` card (e.g., `পেন্ডিং ব্যালেন্স -২৬২০`).
- [x] **5.3 Seller eBalance Verification & Deduction on Delivery Completion (Pages 10–11)**:
  - Self-Delivery Sellers can move orders to `Accept` and `On Delivery` freely, but clicking `Delivered` / completing delivery must check `Seller eBalance` against the order's `Pending Due`:
    - **Case 1 (Insufficient Balance)**: Block completion and show modal/message:
      `"এই অর্ডারটি সম্পন্ন করতে আপনার Seller eBalance-এ ৳[Due] লোড করুন। এরপর অর্ডারটি সম্পন্ন করার মাধ্যমে কাস্টমারকে ক্যাশব্যাক প্রদান করুন এবং নিজেও পয়েন্ট অর্জন করুন আর আকর্ষণীয় পুরস্কারের পথে আরও এক ধাপ এগিয়ে যান। ধন্যবাদ।"`
    - **Case 2 (Sufficient Balance)**: Show confirmation prompt:
      `"আপনার Seller eBalance থেকে ৳[Due] কেটে নেওয়া হবে। আপনি কি নিশ্চিত?"` with **হ্যাঁ** (Yes) and **না** (No) buttons.
  - On confirmation (**হ্যাঁ**), wrap in a database transaction (`DB::transaction`) with idempotency protection (never deduct twice for the same order):
    - Deduct `Pending Due` from `Seller eBalance` and log the `Due Deduction` transaction history.
    - Trigger the existing settlement system unchanged (Customer Pending Cashback & Points move to main e-Balance & Total Points; Seller Pending Reward moves to Total Reward).
- [x] **5.4 Seller Add Money System (Page 11)**:
  - Enable `Add Money` for Online Sellers using the existing Merchant Add Money payment gateway (EPS/MFS/Card), charges, and flow.
  - Credit the loaded funds exclusively to the Seller's `Seller eBalance` (kept separate from `Merchant Balance` with separate transaction history).
- [x] **5.5 Admin Panel Visibility for Self-Delivery Sellers (Pages 11–12)**:
  - Visually distinguish `Self Delivery` orders from `Ettaba Delivery` orders in the Admin Orders table using a distinct row/badge color.
  - Provide Admin views to monitor Self-Delivery Sellers' `Pending Due`, `Order-wise Due`, `Seller eBalance`, `Due Deduction Transaction History`, and a read-only (`OnlyRead`) view of the Seller Panel.

---

## Phase 6: Customer Registration, Cart & Stock Management (Pages 12–13)
- [x] **6.1 Customer Registration Mobile Input Validation (Page 12)**:
  - In Customer Registration Step 2 (`মোবাইল নাম্বার লিখুন`), remove the word `"ইউজার নেম"` from labels/placeholders so it only refers to Mobile Number.
  - Enforce strict input validation: English digits only (no Bangla/English letters or symbols), must start with `01`, and be exactly 11 digits, while preserving the existing live uniqueness check.
- [x] **6.2 Shopping Cart Drawer Bottom Buttons (Page 13)**:
  - Add two buttons at the very bottom of the Shopping Cart drawer below the Checkout button:
    - `"আরো শপিং করুন"` (closes the cart drawer just like the top `×` button).
    - `"সম্পূর্ণ কার্ট মুছুন"` (clears all items from the cart).
- [x] **6.3 Product Stock Restoration & Restock Visibility Fix (Page 13)**:
  - When an order is cancelled by Admin or Seller, or a product is removed from an order, automatically restore the product's stock quantity so it returns to `"স্টকে আছে"`.
  - Fix the caching/query/status bug where a stocked-out product still shows `"স্টক আউট"` on the homepage even after the Seller adds new stock.

---

## Phase 7: Local Merchant Dashboard & Sidebar Updates (Page 14)
- [x] **7.1 Local Merchant Header, Dashboard & Sidebar Cleanup**:
  - Remove the `"Ettaba Admin"` top title bar on mobile view so only the hamburger menu icon and logout icon appear at the top.
  - Change heading `"Local Merchant Dashboard"` to `"Merchant Dashboard"`.
  - Change `"Current e-Balance"` label to `"Merchant e-Balance"`.
  - Add a prominent `"কাস্টমারকে ক্যাশব্যাক দিন"` button below the balance card that navigates to the Manual Pay / Cashback screen.
  - Embed the Next Reward progress card below the cashback button with text: `"যতবেশি ক্যাশব্যাক দিবেন, তত বড় পুরস্কার পাবেন"`.
  - Remove the duplicate `"Withdraw"` menu item from the bottom section of the Merchant sidebar (keep only the upper one).

---

## Phase 8: Product Details, Invoice, Anonymous Orders, Custom Pages & Admin Fixes (Pages 15–18)
- [x] **8.1 Product Details Delivery Guideline Text Update (Page 15)**:
  - Update the `"ডেলিভারি নির্দেশিকা"` text on the Product Details page to:
    `"ইত্তেবা শপ লিমিটেড সমগ্র বাংলাদেশে অনলাইন অর্ডার ও ডেলিভারি সেবা পরিচালনা করছে। তবে কাঁচাবাজার, গ্রোসারি, রেস্টুরেন্ট ও দ্রুত পচনশীল কিছু স্থানীয় পণ্য, নির্দিষ্ট লোকাল এলাকায় ডেলিভারি দেওয়া হয়। তাই অর্ডার করার আগে পণ্যটির ডেলিভারি এলাকা যাচাই করে নিন। এই পণ্যটি ডেলিভারি হয়: [সেলারের নির্ধারিত ডেলিভারি এলাকা]"` (highlight the seller's delivery area in a distinct eye-catching color).
- [x] **8.2 Admin Order Invoice Address & Header Cleanup (Page 16)**:
  - Remove `"Ettaba Admin"` from the top of the printable order/invoice view.
  - Update the `"From"` address block to:
    ```
    From
    Ettaba Shop Limited
    Mohammadpur-1207,
    Dhaka, Bangladesh.
    Phone: +8801911122252
    ```
- [x] **8.3 Anonymous Orders Integration & Validation (Page 16)**:
  - Enable Anonymous Orders so they appear inside the main Orders list for both Admin and Seller (instead of a separate menu).
  - Show an indicator to **Admin only** that the order is an Anonymous Order; **Sellers must see it as a regular order** without knowing it is anonymous.
  - Enforce form validation on Anonymous Order checkout:
    - **Name**: Letters only (no numbers).
    - **Mobile**: English digits only, must start with `01`, exactly 11 digits (no letters or symbols).
    - **Address**: Bangla and English letters and standard punctuation/symbols only.
- [x] **8.4 Dynamic Custom Pages / Policy Blog System (Pages 16–17)**:
  - Build a Page/Blog Builder in the Admin Panel allowing Admin to create, edit, and publish custom URL pages (e.g., Terms & Conditions for Google Play compliance, Refund Policy, Customer/Partner/Seller/Merchant Registration Terms).
  - Visiting the custom URL opens a clean, notice-style public page.
- [x] **8.5 Homepage Category Section Filtering & Random Product Order (Page 17)**:
  - Fix the Homepage Product Section query bug where products from a category assigned to one section (e.g., `"শীতের পোশাক"` in `"শীতের আয়োজন"`) erroneously appear in other sections (`"অর্গানিক পণ্য"`, `"কসমেটিক ও প্রসাধনী"`). Ensure each section strictly displays only its assigned category's products.
  - Randomize product display order so the same products do not repeatedly appear in the exact same position.
- [x] **8.6 Admin Panel FOUC / Slow Table Rendering Fix (Page 18)**:
  - Fix the Flash of Unstyled Content (FOUC) / layout breakdown when opening Admin Category, Homepage Section, and Slider pages so tables and styles render smoothly and immediately.
- [x] **8.7 Remove Office Google Maps Location Link (Page 18)**:
  - Remove/hide the Google Maps office location link from the website/app footer and contact sections (keeping the Mohammadpur, Dhaka address text).

