<!-- Ettaba Shop Header (Section 2.1, 2.2, 2.3) -->
<header class="ettaba-header">
    <div class="container-fluid px-2 px-md-4 px-xl-5">
        <!-- Top Row: Menu Icon, Brand Logo + Slogan, Location & Notification -->
        <div class="d-flex align-items-center justify-content-between mb-2">
            <!-- Left: Menu Icon + Brand Logo & Slogan -->
            <div class="d-flex align-items-center">
                <button onclick="toggleDrawer()" class="btn btn-link text-dark p-1 mr-2" aria-label="Menu" style="font-size: 20px;">
                    <i class="fas fa-bars"></i>
                </button>

                <a href="{{ url('/') }}" class="ettaba-brand-wrap">
                    <img src="{{ asset('assets/images/logo/logo.png') }}"
                         class="ettaba-brand-logo-icon"
                         alt="Ettaba Shop"
                         onerror="this.style.display='none'">
                    <div>
                        <div class="ettaba-brand-title">
                            <span class="text-ettaba-green">Ettaba</span> <span class="text-ettaba-red">Shop</span>
                        </div>
                        <div class="ettaba-brand-slogan">পারিবারিক খরচ হোক সাশ্রয়ী</div>
                    </div>
                </a>
            </div>

            <!-- Right: Location (Address only when logged in; only pin icon when logged out) + Notification Icon -->
            <div class="d-flex align-items-center" style="gap: 6px;">
                @auth
                    <!-- Logged-in: Show Location Pin + Registered Municipality/Union/Ward -->
                    <a href="{{ route('user.profile') }}" class="ettaba-location-pill text-decoration-none" title="আপনার নিবন্ধিত ঠিকানা">
                        <i class="fas fa-map-marker-alt mr-1" style="color: #15803d; font-size: 15px;"></i>
                        <span>{{ !empty($headerLocationText) ? $headerLocationText : 'ঠিকানা যুক্ত করুন' }}</span>
                        <i class="fas fa-chevron-down ml-1 text-muted" style="font-size: 10px;"></i>
                    </a>
                @else
                    <!-- Logged-out: Show ONLY Location Icon (No municipality, union or ward number) -->
                    <span class="ettaba-location-icon-only" title="লোকেশন">
                        <i class="fas fa-map-marker-alt"></i>
                    </span>
                @endguest

                <!-- Notification Bell Icon + Unread Count (Hidden if 0) -->
                <a href="{{ route('website.notices.index') }}" class="ettaba-notify-btn" title="নোটিশ ও নোটিফিকেশন">
                    <i class="fas fa-bell"></i>
                    @if(isset($unreadNoticeCount) && $unreadNoticeCount > 0)
                        <span class="ettaba-notify-badge">{{ $unreadNoticeCount }}</span>
                    @endif
                </a>
            </div>
        </div>

        <!-- Bottom Row: Search Box (Section 2.2) -->
        @if(empty($hideGlobalSearch))
        <div>
            <form action="{{ route('search') }}" method="get">
                <div class="ettaba-search-bar">
                    <i class="fas fa-search ettaba-search-icon"></i>
                    <input type="text"
                           name="keywords"
                           value="{{ request('keywords') }}"
                           class="ettaba-search-input"
                           placeholder="পণ্য বা সেলার খুঁজুন (নাম, পণ্যের কোড বা সেলার কোড ব্যবহার করুন)">
                    <button type="submit" class="ettaba-search-submit">
                        খুঁজুন
                    </button>
                </div>
            </form>
        </div>
        @endif
    </div>
</header>

<!-- Cart Drawer -->
<div id="cartDrawer" class="cart-drawer" v-cloak>
    <div class="cart-drawer-header d-flex justify-content-between align-items-center text-white p-3 border-bottom" style="background: #15803d;">
        <h5 class="mb-0 font-weight-bold"><i class="fas fa-shopping-cart mr-2"></i> শপিং কার্ট</h5>
        <button onclick="toggleCartDrawer()" class="btn btn-sm btn-light text-dark rounded-circle p-2" style="width: 32px; height: 32px; display: flex; align-items: center; justify-content: center;">
            <i class="fas fa-times"></i>
        </button>
    </div>

    <div class="cart-drawer-body flex-grow-1 overflow-auto bg-white p-0">
        <div v-if="cartItems && cartItems.length > 0">
            <ul class="list-group list-group-flush">
                <li class="list-group-item d-flex align-items-center py-3" v-for="(item, index) in cartItems" :key="index">
                    <div class="flex-grow-1">
                        <h6 class="mb-1 font-weight-bold">@{{ item.name }}</h6>
                        <div class="small text-muted mb-2">পয়েন্ট: @{{ item.trp }} | মূল্য: @{{ item.price }}৳</div>

                        <div class="input-group input-group-sm" style="width: 110px;">
                            <div class="input-group-prepend">
                                <button @click="removeItem(index)" class="btn btn-outline-secondary" type="button">-</button>
                            </div>
                            <input type="text" class="form-control text-center font-weight-bold px-1" :value="item.quantity" readonly>
                            <div class="input-group-append">
                                <button @click="addToCart(item.product_id, item.name, item.price, item.trp, item.owner_id, item.tcb, item.rate)" class="btn btn-outline-secondary" type="button">+</button>
                            </div>
                        </div>
                    </div>

                    <div class="ml-3 text-right d-flex flex-column justify-content-between align-items-end h-100">
                        <div class="font-weight-bold text-danger mb-3">@{{ item.price * item.quantity }}৳</div>
                        <button @click="removeItem(index)" class="btn btn-sm btn-light text-danger rounded-circle p-2 shadow-sm" style="width: 32px; height: 32px;">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                </li>
            </ul>
        </div>
        <div v-else class="text-center py-5 mt-5">
            <i class="fas fa-shopping-basket fa-4x text-muted mb-3"></i>
            <h5 class="text-muted font-weight-bold">আপনার কার্ট খালি</h5>
            <p class="text-muted small mb-4">পছন্দের পণ্য কার্টে যোগ করে কেনাকাটা শুরু করুন।</p>
            <button onclick="toggleCartDrawer()" class="btn btn-success rounded-pill px-5 py-2 shadow-sm" style="background: #15803d;">কেনাকাটা শুরু করুন</button>
        </div>
    </div>

    <div v-if="cartItems && cartItems.length > 0" class="cart-drawer-footer bg-light border-top p-4">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <span class="text-muted font-weight-bold">সর্বমোট মূল্য:</span>
            <h4 class="text-danger font-weight-bold mb-0">@{{ cartTotal }}৳</h4>
        </div>
        <a href="{{ route('website.checkout') }}" class="btn btn-danger btn-block rounded-pill py-3 font-weight-bold mb-2 shadow" style="background: #dc2626;">
            অর্ডার সম্পন্ন করুন (Checkout) <i class="fas fa-arrow-right ml-2"></i>
        </a>
        <button class="btn btn-link btn-block text-muted text-decoration-none small font-weight-bold" @click="clearCart()">
            <i class="fas fa-trash-alt mr-1"></i> সম্পূর্ণ কার্ট মুছুন
        </button>
    </div>
</div>
