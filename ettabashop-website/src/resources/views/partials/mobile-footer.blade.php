<div class="mobile-footer d-md-none animate-slide-up">
    <a href="{{ url('/') }}" class="mobile-nav-item {{ Request::is('/') ? 'active' : '' }}">
        <i class="fas fa-home mobile-nav-icon"></i>
        <span>Home</span>
    </a>

    <a href="#" class="mobile-nav-item {{ Request::is('categories*') ? 'active' : '' }}"
        onclick="toggleDrawer(); return false;">
        <i class="fas fa-th-large mobile-nav-icon"></i>
        <span>Explore</span>
    </a>

    <a href="#" class="mobile-nav-item cart-floating-btn shadow-lg">
        <i class="fas fa-shopping-cart mobile-nav-icon"></i>
        <span class="badge badge-pill">0</span>
    </a>

    <a href="#" class="mobile-nav-item {{ Request::is('wishlist*') ? 'active' : '' }}">
        <i class="far fa-heart mobile-nav-icon"></i>
        <span>Wishlist</span>
    </a>

    <a href="{{ route('login') }}"
        class="mobile-nav-item {{ Request::is('login') || Request::is('profile*') ? 'active' : '' }}">
        <i class="far fa-user mobile-nav-icon"></i>
        <span>Profile</span>
    </a>
</div>