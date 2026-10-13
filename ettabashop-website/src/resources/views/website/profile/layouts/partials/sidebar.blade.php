<div class="dash-sidebar-card">
    <!-- User Quick Summary -->
    <div class="text-center pb-3 mb-3 border-bottom">
        <div style="position: relative; display: inline-block; margin-bottom: 10px;">
            <img class="rounded-circle shadow-sm"
                 style="width: 76px; height: 76px; object-fit: cover; border: 3px solid #eff6ff;"
                 src="{{ isset($profile->profile_image) && $profile->profile_image != '' ? asset($profile->profile_image) : asset('assets/images/user.png') }}"
                 alt="User profile">
            <span style="position: absolute; bottom: 2px; right: 2px; width: 14px; height: 14px; background: #10b981; border: 2px solid #fff; border-radius: 50%;" title="Active"></span>
        </div>
        <h6 class="font-weight-bold text-dark mb-1" style="font-size: 15px;">
            {{ !empty($profile->first_name) ? $profile->first_name . ' ' . ($profile->last_name ?? '') : Auth::user()->name }}
        </h6>
        
        <div class="d-inline-flex align-items-center bg-light px-2 py-1 rounded text-muted mb-2" style="font-size: 12px; cursor: pointer; border: 1px solid #e2e8f0;" onclick="copyEid('{{ Auth::user()->referral_code }}')" title="কপি করতে ক্লিক করুন">
            <i class="far fa-copy mr-1 text-primary"></i> eID: <strong class="text-dark ml-1">{{ Auth::user()->referral_code }}</strong>
        </div>

        @if(isset($profile->created_at))
            <div class="text-muted" style="font-size: 11.5px;">
                যোগদান: {{ date('d M Y', strtotime($profile->created_at)) }}
            </div>
        @endif
    </div>

    <!-- Navigation Menu Items -->
    <ul class="dash-menu-list">
        <li class="dash-menu-item">
            <a href="{{ route('user.profile') }}" class="dash-menu-link {{ Request::is('profile/index') ? 'active' : '' }}">
                <div class="link-left">
                    <span class="menu-icon"><i class="fas fa-user-cog"></i></span>
                    <span>প্রোফাইল সেটিংস</span>
                </div>
            </a>
        </li>
        <li class="dash-menu-item">
            <a href="{{ route('profile.order') }}" class="dash-menu-link {{ Request::is('profile/orders') ? 'active' : '' }}">
                <div class="link-left">
                    <span class="menu-icon"><i class="fas fa-shopping-bag"></i></span>
                    <span>অনলাইন অর্ডার</span>
                </div>
            </a>
        </li>
        <li class="dash-menu-item">
            <a href="{{ route('profile.handcash.order') }}" class="dash-menu-link {{ Request::is('profile/hand-cash-orders') ? 'active' : '' }}">
                <div class="link-left">
                    <span class="menu-icon"><i class="fas fa-store"></i></span>
                    <span>মার্চেন্ট ক্যাশব্যাক</span>
                </div>
            </a>
        </li>
        <li class="dash-menu-item">
            <a href="{{ route('profile.withdrawRequests') }}" class="dash-menu-link {{ Request::is('profile/withdraw-requests') ? 'active' : '' }}">
                <div class="link-left">
                    <span class="menu-icon"><i class="fas fa-wallet"></i></span>
                    <span>উত্তোলন রিকোয়েস্ট</span>
                </div>
                <span class="badge badge-light text-primary font-weight-bold" style="font-size: 11px;">
                    ৳ {{ number_format($earnings['totalWithdraw'] ?? 0, 0) }}
                </span>
            </a>
        </li>
        <li class="dash-menu-item">
            <a href="{{ route('profile.withdrawHistories') }}" class="dash-menu-link {{ Request::is('profile/withdraw-histories') ? 'active' : '' }}">
                <div class="link-left">
                    <span class="menu-icon"><i class="fas fa-history"></i></span>
                    <span>উত্তোলন হিস্টোরি</span>
                </div>
            </a>
        </li>
        <li class="dash-menu-item">
            <a href="{{ route('profile.teamTree') }}" class="dash-menu-link {{ Request::is('profile/team-tree') ? 'active' : '' }}">
                <div class="link-left">
                    <span class="menu-icon"><i class="fas fa-sitemap"></i></span>
                    <span>টিম ও ট্রি</span>
                </div>
            </a>
        </li>
        @if(\Illuminate\Support\Facades\Auth::user()->customer_type == 'buy_earn')
            <li class="dash-menu-item">
                <a href="{{ route('profile.trainings') }}" class="dash-menu-link {{ Request::is('profile/trainings') ? 'active' : '' }}">
                    <div class="link-left">
                        <span class="menu-icon"><i class="fas fa-graduation-cap"></i></span>
                        <span>আমার ট্রেনিং</span>
                    </div>
                </a>
            </li>
            <li class="dash-menu-item">
                <a href="{{ route('profile.referral') }}" class="dash-menu-link {{ Request::is('profile/referral') ? 'active' : '' }}">
                    <div class="link-left">
                        <span class="menu-icon"><i class="fas fa-user-plus"></i></span>
                        <span>রেফারেল লিংক</span>
                    </div>
                </a>
            </li>
        @endif
        <li class="dash-menu-item">
            <a href="https://forms.gle/LfUibtvMfJSAsRjP7" target="_blank" class="dash-menu-link">
                <div class="link-left">
                    <span class="menu-icon"><i class="fas fa-handshake"></i></span>
                    <span>পার্টনার আবেদন</span>
                </div>
                <i class="fas fa-external-link-alt text-muted" style="font-size: 11px;"></i>
            </a>
        </li>
        <li class="dash-menu-item pt-2 border-top mt-2">
            <a href="{{ route('logout') }}" 
               onclick="event.preventDefault(); document.getElementById('sidebar-logout-form').submit();" 
               class="dash-menu-link text-danger">
                <div class="link-left">
                    <span class="menu-icon" style="background: #fef2f2; color: #ef4444;"><i class="fas fa-sign-out-alt"></i></span>
                    <span class="text-danger font-weight-500">লগআউট</span>
                </div>
            </a>
            <form id="sidebar-logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                @csrf
            </form>
        </li>
    </ul>
</div>
