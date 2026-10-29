@extends('website.layouts.layout')

@section('content')
<div class="container-fluid px-2 px-md-4 px-xl-5 pt-2 pb-4">

    <!-- 3.1 Main Promotional Banner Slider -->
    <div class="mb-3">
        @include('website.layouts.includes.slider')
    </div>

    <!-- 3.2 Logged-Out Compact Registration & Trial Card Benefits Section -->
    @guest
        <div class="ettaba-guest-cta-card bg-white border shadow-sm mb-3 p-2 p-md-3" style="border-radius: 14px;">
            <div class="row no-gutters align-items-center">
                <!-- Left Column: Free Registration Online Benefits -->
                <div class="col-4 pr-2 border-right">
                    <div class="d-flex align-items-center">
                        <div class="ettaba-guest-icon-wrap mr-2 d-none d-sm-flex">
                            <i class="fas fa-user-plus"></i>
                        </div>
                        <div>
                            <div class="d-flex align-items-center mb-1">
                                <i class="fas fa-user-plus text-success mr-1 d-sm-none" style="font-size: 13px;"></i>
                                <span class="ettaba-guest-heading">ফ্রি রেজিস্ট্রেশন করে</span>
                            </div>
                            <div class="ettaba-guest-subtext">
                                প্রতি অর্ডারে নিন ক্যাশব্যাক, রিওয়ার্ড ও পুরস্কার সুবিধা।
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Middle Column: Free Trial Card Local Benefits -->
                <div class="col-4 px-2 border-right">
                    <div class="d-flex align-items-start">
                        <div class="ettaba-guest-card-icon mr-2 d-none d-sm-flex">
                            <i class="fas fa-id-card"></i>
                        </div>
                        <div>
                            <div class="ettaba-guest-heading mb-1">
                                ফ্রি ট্রায়াল কার্ড সংগ্রহ করে উপভোগ করুন
                            </div>
                            <ul class="list-unstyled mb-0 ettaba-guest-bullets">
                                <li><i class="fas fa-check-circle text-success mr-1"></i>লোকাল মার্চেন্ট সুবিধা</li>
                                <li><i class="fas fa-check-circle text-success mr-1"></i>লোকাল হাসপাতাল ও ক্লিনিক সুবিধা</li>
                            </ul>
                        </div>
                    </div>
                </div>

                <!-- Right Column: Login & Free Registration Buttons -->
                <div class="col-4 pl-2">
                    <a href="{{ route('login') }}" class="ettaba-guest-login-btn w-100 mb-1 text-decoration-none">
                        <i class="fas fa-user mr-1"></i> লগইন করুন
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
            $rewardDetailUrl = isset($nextReward) && !empty($nextReward->id) ? route('profile.rewards.detail', $nextReward->id) : route('profile.rewards');
        @endphp
        <div class="ettaba-reward-home-card bg-white border shadow-sm mb-3 p-2 p-md-3" style="border-radius: 14px;">
            <!-- Top Header Row -->
            <div class="d-flex align-items-center justify-content-between mb-2 pb-1 border-bottom">
                <a href="{{ $rewardDetailUrl }}" class="d-flex align-items-center text-decoration-none">
                    <i class="fas fa-gift text-danger mr-2" style="font-size: 18px;"></i>
                    <span class="ettaba-reward-card-heading">আপনার পরবর্তী পুরস্কার</span>
                </a>
                <a href="{{ route('profile.rewards') }}" class="ettaba-reward-all-btn text-decoration-none">
                    সকল পুরস্কারের তালিকা দেখুন <i class="fas fa-arrow-right ml-1"></i>
                </a>
            </div>

            <!-- Reward Body Row -->
            <div class="d-flex align-items-center">
                <!-- Left: Specific Reward Image -->
                <a href="{{ $rewardDetailUrl }}" class="ettaba-reward-gift-thumb mr-2 mr-md-3 text-decoration-none">
                    @if(!empty($rewardImg))
                        <img src="{{ config('app.image_url') . $rewardImg }}"
                             onerror="this.src='{{ asset($rewardImg) }}'"
                             alt="{{ $rewardName }}">
                    @else
                        <div class="ettaba-reward-gift-fallback">
                            <i class="fas fa-gift"></i>
                        </div>
                    @endif
                </a>

                <!-- Right: Stats + Progress Bar -->
                <div class="flex-grow-1">
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
            </div>
        </div>
    @endauth

    <!-- 6. Category Section (Both Logged-in & Logged-out Homepages) -->
    @php
        $defaultRefCategories = [
            ['name_bn' => 'মুদি ও নিত্যপ্রয়োজনীয়', 'slug' => 'daily-needs', 'icon_class' => 'fas fa-shopping-cart', 'icon_color' => '#dc2626', 'image' => null],
            ['name_bn' => 'ফ্যাশন ও লাইফস্টাইল', 'slug' => 'fashion-and-lifestyle', 'icon_class' => 'fas fa-tshirt', 'icon_color' => '#16a34a', 'image' => null],
            ['name_bn' => 'জুয়েলারি ও কসমেটিকস', 'slug' => 'jewelry-and-cosmetics', 'icon_class' => 'fas fa-gem', 'icon_color' => '#2563eb', 'image' => null],
            ['name_bn' => 'রেস্টুরেন্ট ও ফুড', 'slug' => 'restaurant-and-food', 'icon_class' => 'fas fa-utensils', 'icon_color' => '#ea580c', 'image' => null],
            ['name_bn' => 'ক্লিনিক ও হাসপাতাল', 'slug' => 'clinic-and-hospital', 'icon_class' => 'fas fa-stethoscope', 'icon_color' => '#9333ea', 'image' => null],
            ['name_bn' => 'ইলেকট্রনিক্স ও গ্যাজেট', 'slug' => 'electronics-and-gadget', 'icon_class' => 'fas fa-desktop', 'icon_color' => '#0284c7', 'image' => null],
        ];
        $displayHomeCats = (isset($homeCategories) && count($homeCategories) > 0) ? $homeCategories : $defaultRefCategories;
    @endphp
    <div class="ettaba-category-strip mb-3">
        <div class="ettaba-cat-grid" style="display:grid; grid-template-columns:repeat(7, 1fr); gap:6px;">
            @foreach(collect($displayHomeCats)->take(6) as $cat)
                @php
                    $catTitle = data_get($cat, 'name_bn') ?: data_get($cat, 'name_en') ?: data_get($cat, 'name', '');
                    $catSlug = data_get($cat, 'slug', '');
                    $catColor = data_get($cat, 'icon_color') ?: data_get($cat, 'color', '#15803d');
                    $catIcon = data_get($cat, 'icon_class');
                    if (empty($catIcon)) {
                        if (str_contains($catTitle, 'ফ্যাশন') || str_contains(strtolower($catSlug), 'fashion')) {
                            $catIcon = 'fas fa-tshirt';
                            $catColor = '#16a34a';
                        } elseif (str_contains($catTitle, 'জুয়েলারি') || str_contains($catTitle, 'কসমেটিক') || str_contains(strtolower($catSlug), 'jewel')) {
                            $catIcon = 'fas fa-gem';
                            $catColor = '#2563eb';
                        } elseif (str_contains($catTitle, 'রেস্টুরেন্ট') || str_contains($catTitle, 'ফুড') || str_contains(strtolower($catSlug), 'food')) {
                            $catIcon = 'fas fa-utensils';
                            $catColor = '#ea580c';
                        } elseif (str_contains($catTitle, 'ক্লিনিক') || str_contains($catTitle, 'হাসপাতাল') || str_contains(strtolower($catSlug), 'hospital')) {
                            $catIcon = 'fas fa-stethoscope';
                            $catColor = '#9333ea';
                        } elseif (str_contains($catTitle, 'ইলেকট্রনিক') || str_contains($catTitle, 'গ্যাজেট') || str_contains(strtolower($catSlug), 'electronic')) {
                            $catIcon = 'fas fa-desktop';
                            $catColor = '#0284c7';
                        } elseif (str_contains($catTitle, 'কার্ড') || str_contains($catTitle, 'সাবস্ক্রিপশন')) {
                            $catIcon = 'fas fa-id-card';
                            $catColor = '#dc2626';
                        } else {
                            $catIcon = 'fas fa-shopping-cart';
                        }
                    }
                    $catImg = data_get($cat, 'image');
                    $catUrl = !empty($catSlug) ? route('categoryProducts', ['slug' => $catSlug]) : route('allProducts');
                @endphp
                <a href="{{ $catUrl }}" class="ettaba-cat-card text-decoration-none"
                   style="background:#ffffff; border:1px solid #e2e8f0; border-radius:10px; padding:8px 3px 6px; display:flex; flex-direction:column; align-items:center; justify-content:flex-start; text-align:center;">
                    <div class="ettaba-cat-icon-box" style="color: {{ $catColor }}; width:34px; height:34px; display:flex; align-items:center; justify-content:center; font-size:22px; margin-bottom:4px;">
                        @if(!empty($catImg))
                            <img src="{{ config('app.image_url') . $catImg }}"
                                 onerror="this.src='{{ asset($catImg) }}'"
                                 style="max-width:30px; max-height:30px; object-fit:contain;"
                                 alt="{{ $catTitle }}">
                        @else
                            <i class="{{ $catIcon }}"></i>
                        @endif
                    </div>
                    <div class="ettaba-cat-title" style="font-size:10.5px; font-weight:700; color:#0f172a; line-height:1.15;">{{ $catTitle }}</div>
                </a>
            @endforeach

            <!-- "সব ক্যাটাগরি" Button -->
            <a href="javascript:void(0)" onclick="$('#allCategoriesModal').modal('show')" class="ettaba-cat-card text-decoration-none"
               style="background:#ffffff; border:1px solid #e2e8f0; border-radius:10px; padding:8px 3px 6px; display:flex; flex-direction:column; align-items:center; justify-content:flex-start; text-align:center;">
                <div class="ettaba-cat-icon-box" style="color: #475569; width:34px; height:34px; display:flex; align-items:center; justify-content:center; font-size:22px; margin-bottom:4px;">
                    <i class="fas fa-th-large"></i>
                </div>
                <div class="ettaba-cat-title" style="font-size:10.5px; font-weight:700; color:#0f172a; line-height:1.15;">সব<br>ক্যাটাগরি</div>
            </a>
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
                <div class="row no-gutters">
                    @if(isset($allCategoriesList) && count($allCategoriesList) > 0)
                        @foreach($allCategoriesList as $catItem)
                            <div class="col-4 col-sm-3 p-1">
                                <a href="{{ !empty($catItem->slug) ? route('categoryProducts', ['slug' => $catItem->slug]) : route('allProducts') }}"
                                   class="d-flex flex-column align-items-center justify-content-center p-2 border rounded text-center text-decoration-none h-100 bg-light hover-shadow">
                                    @if(!empty($catItem->image))
                                        <img src="{{ asset($catItem->image) }}" style="width: 36px; height: 36px; object-fit: contain;" class="mb-1">
                                    @else
                                        <i class="{{ $catItem->icon_class ?: 'fas fa-shopping-bag' }} mb-1" style="font-size: 22px; color: {{ $catItem->icon_color ?: '#15803d' }};"></i>
                                    @endif
                                    <span class="text-dark font-weight-bold" style="font-size: 12px; line-height: 1.2;">
                                        {{ $catItem->name_bn ?: $catItem->name_en }}
                                    </span>
                                </a>
                            </div>
                        @endforeach
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
