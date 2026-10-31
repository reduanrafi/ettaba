@extends('website.layouts.layout')

@section('content')
<div class="container-fluid px-2 px-md-4 px-xl-5 pt-2 pb-4">

    <!-- 3.1 Main Promotional Banner Slider -->
    <div class="mb-3">
        @include('website.layouts.includes.slider')
    </div>

    <!-- 3.2 Logged-Out Compact Registration & Trial Card Benefits Section (2-Column Layout) -->
    @guest
        <div class="ettaba-guest-cta-card bg-white border shadow-sm mb-3 py-2 px-2 p-md-3" style="border-radius: 14px;">
            <div class="row no-gutters align-items-center">
                <!-- Left Column: Stacked Free Registration + Trial Card Benefits -->
                <div class="col-7 col-md-8 pr-2 border-right">
                    <!-- Top Block: Free Registration -->
                    <div class="d-flex align-items-start mb-1 pb-1" style="border-bottom: 1px dashed #e2e8f0;">
                        <div class="ettaba-guest-icon-wrap mr-2">
                            <i class="fas fa-user-plus"></i>
                        </div>
                        <div class="flex-grow-1">
                            <div class="ettaba-guest-heading">ফ্রি রেজিস্ট্রেশন করে</div>
                            <div class="ettaba-guest-subtext">
                                প্রতি অর্ডারে নিন ক্যাশব্যাক, রিওয়ার্ড ও পুরস্কার সুবিধা।
                            </div>
                        </div>
                    </div>

                    <!-- Bottom Block: Free Trial Card -->
                    <div class="d-flex align-items-start pt-1">
                        <div class="ettaba-guest-card-icon mr-2">
                            <i class="fas fa-id-card"></i>
                        </div>
                        <div class="flex-grow-1">
                            <div class="ettaba-guest-heading mb-1">
                                ফ্রি ট্রায়াল কার্ড সংগ্রহ করে উপভোগ করুন
                            </div>
                            <ul class="list-unstyled mb-0 ettaba-guest-bullets">
                                <li><i class="fas fa-check-circle text-success mr-1"></i>লোকাল মার্চেন্ট কেনাকাটা সুবিধা</li>
                                <li><i class="fas fa-check-circle text-success mr-1"></i>লোকাল হাসপাতাল, ক্লিনিক ও সার্ভিসের সুবিধা</li>
                            </ul>
                        </div>
                    </div>
                </div>

                <!-- Right Column: Login & Free Registration Buttons -->
                <div class="col-5 col-md-4 pl-2 d-flex flex-column justify-content-center">
                    <a href="{{ route('login') }}" class="ettaba-guest-login-btn w-100 mb-2 text-decoration-none">
                        <div class="d-flex align-items-center justify-content-center">
                            <i class="fas fa-user mr-1"></i>
                            <div class="text-center lh-1">
                                <div class="login-main-txt">লগইন করুন</div>
                                <div class="login-sub-txt">(একাউন্ট আছে)</div>
                            </div>
                        </div>
                    </a>
                    <a href="{{ route('register') }}" class="ettaba-guest-reg-btn w-100 text-decoration-none">
                        <div class="d-flex align-items-center justify-content-center">
                            <i class="fas fa-plus-circle text-success mr-1"></i>
                            <div class="text-center lh-1">
                                <div class="reg-main-txt">নতুন একাউন্ট খুলুন</div>
                                <div class="reg-sub-txt">(সম্পূর্ণ ফ্রি)</div>
                            </div>
                        </div>
                    </a>
                </div>
            </div>
        </div>
    @endguest

    <!-- 3.3, 4 & 5. Logged-In Customer Account Info & Next Reward Section -->
    @auth
        <!-- 4. Customer Account 6-Card Info -->
        <div class="mb-3">
            @include('website.layouts.home_sections.points')
        </div>

        <!-- 5. Next Reward Progress Card -->
        @php
            $currentPoints = floatval($userPoints ?? ($statistics['totalPoint'] ?? 0));
            $targetPoints = isset($nextReward) && $nextReward->required_points > 0 ? floatval($nextReward->required_points) : 300;
            $neededPoints = max(0, $targetPoints - $currentPoints);
            $progressPercent = $targetPoints > 0 ? min(100, round(($currentPoints / $targetPoints) * 100, 1)) : 0;
            $rewardName = isset($nextReward) && !empty($nextReward->reward_name) ? $nextReward->reward_name : 'Ettaba Smart Customer';
            $rewardImg = isset($nextReward) && !empty($nextReward->image) ? $nextReward->image : null;
            $rewardImgUrl = isset($nextReward) && !empty($nextReward->image_url)
                ? $nextReward->image_url
                : (!empty($rewardImg) ? (rtrim(preg_replace('#/storage/?$#', '', config('app.image_url')), '/') . '/' . ltrim($rewardImg, '/')) : null);
            $rewardDetailUrl = isset($nextReward) && !empty($nextReward->id) ? route('profile.rewards.detail', $nextReward->id) : route('profile.rewards');
        @endphp
        <div class="ettaba-reward-home-card bg-white border shadow-sm mb-3 p-2 p-md-3" style="border-radius: 14px;">
            <!-- Top Header Row -->
            <div class="d-flex align-items-center justify-content-between mb-2 pb-1 border-bottom">
                <a href="{{ $rewardDetailUrl }}" class="d-flex align-items-center text-decoration-none" style="min-width: 0;">
                    <i class="fas fa-gift text-danger mr-1 mr-sm-2" style="font-size: 18px;"></i>
                    <span class="ettaba-reward-card-heading">আপনার পরবর্তী পুরস্কার</span>
                </a>
                <a href="{{ route('profile.rewards') }}" class="ettaba-see-all-btn text-decoration-none">
                    সকল পুরস্কার দেখুন <i class="fas fa-arrow-right ml-1"></i>
                </a>
            </div>

            <!-- Reward Body Row (Entire area clickable to go to reward details) -->
            <a href="{{ $rewardDetailUrl }}" class="d-flex align-items-center text-decoration-none text-reset ettaba-reward-body-link">
                <!-- Left: Specific Reward Image -->
                <div class="ettaba-reward-gift-thumb mr-2 mr-md-3">
                    @if(!empty($rewardImg))
                        <img src="{{ $rewardImgUrl }}"
                             onerror="this.onerror=null; this.src='{{ asset($rewardImg) }}';"
                             alt="{{ $rewardName }}">
                    @else
                        <div class="ettaba-reward-gift-fallback">
                            <i class="fas fa-gift"></i>
                        </div>
                    @endif
                </div>

                <!-- Right: Stats + Progress Bar -->
                <div class="flex-grow-1" style="min-width: 0;">
                    <div class="row no-gutters text-center align-items-center mb-2">
                        <div class="col-3 border-right px-1 text-left">
                            <div class="ettaba-rw-lbl">মোট পয়েন্ট</div>
                            <div class="ettaba-rw-val-red">{{ number_format($currentPoints, 2) }}</div>
                        </div>
                        <div class="col-5 border-right px-1">
                            <div class="ettaba-rw-lbl">পরবর্তী পুরস্কার পেতে</div>
                            <div class="ettaba-rw-val-dark">{{ number_format($neededPoints, 2) }} <small>পয়েন্ট বাকি</small></div>
                        </div>
                        <div class="col-4 pl-2 text-right">
                            <div class="ettaba-rw-lbl">পরবর্তী পুরস্কার</div>
                            <div class="ettaba-rw-val-green">{{ number_format($targetPoints, 0) }} পয়েন্ট</div>
                            <div class="ettaba-rw-subname text-truncate" title="{{ $rewardName }}">{{ $rewardName }}</div>
                        </div>
                    </div>

                    <!-- Progress Bar -->
                    <div class="progress" style="height: 8px; border-radius: 10px; background: #e2e8f0;">
                        <div class="progress-bar"
                             role="progressbar"
                             style="width: {{ max(4, $progressPercent) }}%; background: linear-gradient(90deg, #dc2626 0%, #b91c1c 100%); border-radius: 10px;"
                             aria-valuenow="{{ $progressPercent }}"
                             aria-valuemin="0"
                             aria-valuemax="100"></div>
                    </div>
                    <div class="d-flex justify-content-between mt-1" style="font-size: 10.5px; font-weight: 700;">
                        <span class="text-danger">{{ number_format($currentPoints, 2) }}</span>
                        <span class="text-muted">{{ number_format($targetPoints, 0) }}</span>
                    </div>
                </div>
            </a>
        </div>
    @endauth

    <!-- 6. Category Section (Both Logged-in & Logged-out Homepages - 7 Categories + 1 All Button in 2 Rows) -->
    @php
        $defaultRefCategories = [
            ['name_bn' => 'গ্রোসারি', 'slug' => 'daily-needs', 'icon_class' => 'fas fa-shopping-basket', 'icon_color' => '#059669', 'icon_bg' => '#ecfdf5', 'image' => null],
            ['name_bn' => 'কাঁচা বাজার', 'slug' => 'fresh-market', 'icon_class' => 'fas fa-carrot', 'icon_color' => '#ea580c', 'icon_bg' => '#fff7ed', 'image' => null],
            ['name_bn' => 'মাছ-মাংস', 'slug' => 'fish-and-meat', 'icon_class' => 'fas fa-fish', 'icon_color' => '#2563eb', 'icon_bg' => '#eff6ff', 'image' => null],
            ['name_bn' => 'খাবার ও রেস্টুরেন্ট', 'slug' => 'restaurant-and-food', 'icon_class' => 'fas fa-utensils', 'icon_color' => '#dc2626', 'icon_bg' => '#fef2f2', 'image' => null],
            ['name_bn' => 'রিচার্জ, ডাটা ও মিনিট', 'slug' => 'recharge-and-data', 'icon_class' => 'fas fa-mobile-alt', 'icon_color' => '#7c3aed', 'icon_bg' => '#f5f3ff', 'image' => null],
            ['name_bn' => 'ঔষধ ও ফার্মেসী', 'slug' => 'clinic-and-hospital', 'icon_class' => 'fas fa-pills', 'icon_color' => '#16a34a', 'icon_bg' => '#f0fdf4', 'image' => null],
            ['name_bn' => 'ফ্যাশন ও লাইফস্টাইল', 'slug' => 'fashion-and-lifestyle', 'icon_class' => 'fas fa-tshirt', 'icon_color' => '#db2777', 'icon_bg' => '#fdf2f8', 'image' => null],
        ];
        $displayHomeCats = (isset($homeCategories) && count($homeCategories) > 0) ? $homeCategories : $defaultRefCategories;

        $resolveCatVisuals = function ($cat, $idx = 0) {
            $catTitle = data_get($cat, 'name_bn') ?: data_get($cat, 'name_en') ?: data_get($cat, 'name', '');
            $catSlug = strtolower((string) data_get($cat, 'slug', ''));
            $catIcon = data_get($cat, 'icon_class') ?: data_get($cat, 'icon');
            $catColor = data_get($cat, 'icon_color');
            $catBg = data_get($cat, 'icon_bg');

            $palettes = [
                ['bg' => '#ecfdf5', 'color' => '#059669', 'icon' => 'fas fa-shopping-basket'],
                ['bg' => '#fff7ed', 'color' => '#ea580c', 'icon' => 'fas fa-carrot'],
                ['bg' => '#eff6ff', 'color' => '#2563eb', 'icon' => 'fas fa-fish'],
                ['bg' => '#fef2f2', 'color' => '#dc2626', 'icon' => 'fas fa-utensils'],
                ['bg' => '#f5f3ff', 'color' => '#7c3aed', 'icon' => 'fas fa-mobile-alt'],
                ['bg' => '#f0fdf4', 'color' => '#16a34a', 'icon' => 'fas fa-pills'],
                ['bg' => '#fdf2f8', 'color' => '#db2777', 'icon' => 'fas fa-tshirt'],
                ['bg' => '#eef2ff', 'color' => '#4f46e5', 'icon' => 'fas fa-tv'],
                ['bg' => '#f0f9ff', 'color' => '#0284c7', 'icon' => 'fas fa-mobile'],
                ['bg' => '#fffbeb', 'color' => '#d97706', 'icon' => 'fas fa-paint-brush'],
            ];
            $defaultPalette = $palettes[$idx % count($palettes)];

            if (str_contains($catTitle, 'গ্রোসারি') || str_contains($catTitle, 'মুদি') || str_contains($catSlug, 'daily') || str_contains($catSlug, 'grocery')) {
                $catIcon = $catIcon ?: 'fas fa-shopping-basket';
                $catColor = $catColor ?: '#059669';
                $catBg = $catBg ?: '#ecfdf5';
            } elseif (str_contains($catTitle, 'কাঁচা') || str_contains($catTitle, 'সবজি')) {
                $catIcon = $catIcon ?: 'fas fa-carrot';
                $catColor = $catColor ?: '#ea580c';
                $catBg = $catBg ?: '#fff7ed';
            } elseif (str_contains($catTitle, 'মাছ') || str_contains($catTitle, 'মাংস')) {
                $catIcon = $catIcon ?: 'fas fa-fish';
                $catColor = $catColor ?: '#2563eb';
                $catBg = $catBg ?: '#eff6ff';
            } elseif (str_contains($catTitle, 'রেস্টুরেন্ট') || str_contains($catTitle, 'খাবার') || str_contains($catTitle, 'ফুড') || str_contains($catSlug, 'food')) {
                $catIcon = $catIcon ?: 'fas fa-utensils';
                $catColor = $catColor ?: '#dc2626';
                $catBg = $catBg ?: '#fef2f2';
            } elseif (str_contains($catTitle, 'রিচার্জ') || str_contains($catTitle, 'ডাটা') || str_contains($catTitle, 'মোবাইল')) {
                $catIcon = $catIcon ?: 'fas fa-mobile-alt';
                $catColor = $catColor ?: '#7c3aed';
                $catBg = $catBg ?: '#f5f3ff';
            } elseif (str_contains($catTitle, 'ঔষধ') || str_contains($catTitle, 'ফার্মেসী') || str_contains($catTitle, 'ক্লিনিক') || str_contains($catTitle, 'হাসপাতাল') || str_contains($catSlug, 'hospital')) {
                $catIcon = $catIcon ?: 'fas fa-pills';
                $catColor = $catColor ?: '#16a34a';
                $catBg = $catBg ?: '#f0fdf4';
            } elseif (str_contains($catTitle, 'অর্গানিক')) {
                $catIcon = $catIcon ?: 'fas fa-seedling';
                $catColor = $catColor ?: '#10b981';
                $catBg = $catBg ?: '#ecfdf5';
            } elseif (str_contains($catTitle, 'ফ্যাশন') || str_contains($catSlug, 'fashion')) {
                $catIcon = $catIcon ?: 'fas fa-tshirt';
                $catColor = $catColor ?: '#db2777';
                $catBg = $catBg ?: '#fdf2f8';
            } elseif (str_contains($catTitle, 'জুয়েলারি') || str_contains($catTitle, 'কসমেটিক') || str_contains($catSlug, 'jewel')) {
                $catIcon = $catIcon ?: 'fas fa-gem';
                $catColor = $catColor ?: '#2563eb';
                $catBg = $catBg ?: '#eff6ff';
            } elseif (str_contains($catTitle, 'ইলেকট্রনিক') || str_contains($catTitle, 'ইলেক্ট্রনিক') || str_contains($catTitle, 'গ্যাজেট') || str_contains($catSlug, 'electronic')) {
                $catIcon = $catIcon ?: 'fas fa-tv';
                $catColor = $catColor ?: '#4f46e5';
                $catBg = $catBg ?: '#eef2ff';
            } elseif (str_contains($catTitle, 'কার্ড') || str_contains($catTitle, 'সাবস্ক্রিপশন')) {
                $catIcon = $catIcon ?: 'fas fa-id-card';
                $catColor = $catColor ?: '#dc2626';
                $catBg = $catBg ?: '#fef2f2';
            } else {
                $catIcon = $catIcon ?: $defaultPalette['icon'];
                $catColor = $catColor ?: $defaultPalette['color'];
                $catBg = $catBg ?: $defaultPalette['bg'];
            }

            // Ensure icon class is FontAwesome class string (not an uploaded file path)
            if (str_contains($catIcon, '/') || str_contains($catIcon, '.')) {
                $catIcon = $defaultPalette['icon'];
            }

            return [
                'title' => $catTitle,
                'slug' => data_get($cat, 'slug', ''),
                'icon' => $catIcon,
                'color' => $catColor,
                'bg' => $catBg,
            ];
        };
    @endphp
    <div class="ettaba-category-strip mb-3">
        <!-- Centered "অনলাইন ক্যাটাগরি" Section Heading -->
        <div class="ettaba-online-cat-header d-flex align-items-center justify-content-center mb-2">
            <span class="ettaba-cat-line"></span>
            <div class="d-flex align-items-center mx-2 px-1">
                <i class="fas fa-store text-success mr-1" style="font-size: 16px;"></i>
                <h4 class="mb-0 font-weight-bold" style="font-size: 17px; color: #0f172a; white-space: nowrap;">অনলাইন ক্যাটাগরি</h4>
            </div>
            <span class="ettaba-cat-line"></span>
        </div>

        <div class="ettaba-online-cat-card bg-white border shadow-sm p-2 p-md-3" style="border-radius: 16px;">
            <div class="ettaba-cat-grid-8">
                @foreach(collect($displayHomeCats)->take(7)->values() as $idx => $cat)
                    @php
                        $cv = $resolveCatVisuals($cat, $idx);
                        $catUrl = !empty($cv['slug']) ? route('categoryProducts', ['slug' => $cv['slug']]) : route('allProducts');
                    @endphp
                    <a href="{{ $catUrl }}" class="ettaba-cat-item-v2 text-decoration-none">
                        <div class="ettaba-cat-icon-circle" style="background-color: {{ $cv['bg'] }}; color: {{ $cv['color'] }};">
                            <i class="{{ $cv['icon'] }}"></i>
                        </div>
                        <span class="ettaba-cat-name-v2">{{ $cv['title'] }}</span>
                    </a>
                @endforeach

                <!-- 8th Item: "সব ক্যাটাগরি" Button -->
                <a href="javascript:void(0)" onclick="$('#allCategoriesModal').modal('show')" class="ettaba-cat-item-v2 text-decoration-none">
                    <div class="ettaba-cat-icon-circle" style="background-color: #f1f5f9; color: #475569;">
                        <i class="fas fa-th-large"></i>
                    </div>
                    <span class="ettaba-cat-name-v2">সব ক্যাটাগরি</span>
                </a>
            </div>
        </div>
    </div>

    <!-- 7. Dynamic Homepage Product Sections (8 Sections in Order) -->
    <div id="product-sections">
        @foreach($homeSectionsData as $secData)
            @php
                $sec = $secData['section'];
                $secProducts = $secData['products'];
                $secIcon = data_get($sec, 'icon') ?: data_get($sec, 'icon_class') ?: 'fas fa-bolt';
                $secIconColor = data_get($sec, 'icon_color') ?: '#f59e0b';
            @endphp
            <section class="ettaba-product-section mb-4">
                <!-- Section Header -->
                <div class="d-flex align-items-center justify-content-between mb-2 pb-1">
                    <div class="d-flex align-items-center">
                        <span class="mr-2" style="color: {{ $secIconColor }}; font-size: 20px;">
                            <i class="{{ $secIcon }}"></i>
                        </span>
                        <h4 class="ettaba-section-heading mb-0">{{ $sec->title_bn }}</h4>
                    </div>
                    <a href="{{ route('website.sectionProducts', ['slug' => $sec->slug]) }}" class="ettaba-see-all-btn text-decoration-none">
                        সব দেখুন <i class="fas fa-arrow-right ml-1"></i>
                    </a>
                </div>

                <!-- Products Grid -->
                @if(count($secProducts) > 0)
                    <div class="row no-gutters mx-n1">
                        @foreach($secProducts as $product)
                            <div class="col-6 col-md-4 col-lg-3 p-1 mb-1">
                                @include('website.layouts.home_sections.modern_product_item', ['product' => $product])
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="bg-white border rounded p-3 text-center text-muted small">
                        এই সেকশনে শীঘ্রই নতুন পণ্য যুক্ত হচ্ছে।
                    </div>
                @endif
            </section>
        @endforeach
    </div>

</div>

<!-- All Categories Modal -->
<div class="modal fade" id="allCategoriesModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 16px; overflow: hidden;">
            <div class="modal-header text-white py-3" style="background: #15803d;">
                <h5 class="modal-title font-weight-bold"><i class="fas fa-th-large mr-2"></i> সকল ক্যাটাগরি</h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body p-3">
                <div class="ettaba-cat-grid-8">
                    @if(isset($allCategoriesList) && count($allCategoriesList) > 0)
                        @foreach($allCategoriesList as $idx => $catItem)
                            @php
                                $cvModal = $resolveCatVisuals($catItem, $idx);
                            @endphp
                            <a href="{{ !empty($cvModal['slug']) ? route('categoryProducts', ['slug' => $cvModal['slug']]) : route('allProducts') }}"
                               class="ettaba-cat-item-v2 text-decoration-none">
                                <div class="ettaba-cat-icon-circle" style="background-color: {{ $cvModal['bg'] }}; color: {{ $cvModal['color'] }};">
                                    <i class="{{ $cvModal['icon'] }}"></i>
                                </div>
                                <span class="ettaba-cat-name-v2">
                                    {{ $cvModal['title'] }}
                                </span>
                            </a>
                        @endforeach
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
