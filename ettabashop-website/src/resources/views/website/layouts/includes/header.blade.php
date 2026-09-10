<!-- Topbar Start -->
<header class="modern-header">
    <div class="container-fluid px-xl-5">
        <!-- Desktop Info Bar -->
        <div class="row bg-light py-1 px-xl-5 d-none d-lg-flex border-bottom mb-2">
            <div class="col-lg-6">
                <div class="d-inline-flex align-items-center small text-muted">
                    <span class="mr-3"><i class="fas fa-phone-alt mr-2 text-primary"></i> 01911122252</span>
                    <span class="mr-3"><i class="fas fa-envelope mr-2 text-primary"></i> ettabashop.crm@gmail.com</span>
                </div>
            </div>
            <div class="col-lg-6 text-right">
                <div class="d-inline-flex align-items-center small">
                    <a href="" class="text-muted mr-3">FAQ</a>
                    <a href="" class="text-muted mr-3">Support</a>
                </div>
            </div>
        </div>

        <div class="header-main">
            <!-- Mobile Layout (Top Row) -->
            <div class="header-mobile-top d-lg-none d-flex justify-content-between align-items-center w-100">
                <div style="width: 40px;">
                    <button onclick="toggleDrawer()" class="btn btn-link text-dark p-0">
                        <i class="fas fa-bars fa-lg text-primary"></i>
                    </button>
                </div>
                <a href="{{ url('/') }}" class="text-decoration-none text-center">
                    <img src="{{ asset('assets/images/logo/logo.png') }}" style="max-height: 45px; width: auto;"
                        alt="Logo">
                </a>
                <div style="width: 40px;"></div>
            </div>

            <!-- Mobile Layout (Search Row) -->
            <div class="header-mobile-search d-lg-none">
                <form action="{{ route('search') }}" method="get">
                    <div class="search-container">
                        <input type="text" name="keywords" class="search-input"
                            placeholder="{{ __('titles.searchPlaceholder') }}">
                        <button type="submit" class="btn btn-primary rounded-pill px-3 mr-1">
                            <i class="fa fa-search"></i>
                        </button>
                    </div>
                </form>
            </div>

            <!-- Desktop Layout -->
            <div class="row align-items-center d-none d-lg-flex">
                <div class="col-lg-3">
                    <a href="{{ url('/') }}" class="text-decoration-none">
                        <img src="{{ asset('assets/images/logo/logo.png') }}" class="img-fluid"
                            style="max-height: 60px;" alt="Ettaba Shop">
                    </a>
                </div>

                <div class="col-lg-6">
                    <form action="{{ route('search') }}" method="get">
                        <div class="search-container">
                            <input type="text" name="keywords" class="search-input"
                                placeholder="{{ __('titles.searchPlaceholder') }}">
                            <button type="submit" class="btn btn-primary rounded-pill px-4 mr-1">
                                <i class="fa fa-search mr-2"></i> SEARCH
                            </button>
                        </div>
                    </form>
                </div>

                <div class="col-lg-3 d-flex justify-content-end align-items-center">
                    <a href="#" class="nav-icon-btn mx-2" data-toggle="modal" data-target="#directOrder">
                        <i class="fas fa-clipboard-list fa-lg"></i>
                    </a>

                    <a href="#" class="nav-icon-btn mx-2" onclick="toggleCartDrawer(); return false;">
                        <i class="fas fa-shopping-cart fa-lg"></i>
                        <span class="badge">@{{ cartItems.length }}</span>
                    </a>

                    @auth
                        <div class="dropdown ml-3">
                            <a href="#"
                                class="btn btn-outline-primary rounded-pill px-3 py-1 dropdown-toggle d-flex align-items-center shadow-sm"
                                data-toggle="dropdown">
                                <i class="far fa-user-circle mr-2 fa-lg"></i> {{ Auth::user()->name }}
                            </a>
                            <div class="dropdown-menu dropdown-menu-right shadow-lg border-0 py-2 mt-2">
                                <a class="dropdown-item py-2" href="{{ route('user.profile') }}"><i
                                        class="fas fa-user-edit mr-2 text-primary"></i> Profile</a>
                                @if (Auth::user()->customer_type === 'direct_selling')
                                    <a class="dropdown-item py-2 text-success font-weight-bold" href="{{ route('direct-seller.dashboard') }}"><i
                                            class="fas fa-store mr-2"></i> Direct Selling Panel</a>
                                @endif
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item py-2 text-danger font-weight-bold" href="{{ route('logout') }}"><i
                                        class="fas fa-sign-out-alt mr-2"></i> Logout</a>
                            </div>
                        </div>
                    @else
                        <a href="{{ route('login') }}" class="btn btn-outline-primary rounded-pill px-4 shadow-sm ml-3">Login</a>
                        <a href="{{ route('register') }}" class="btn btn-primary rounded-pill px-4 shadow-sm ml-2">Register</a>
                    @endauth
                </div>
            </div>
        </div>
    </div>
</header>

<!-- Modals (Kept legacy logic for functionality) -->
<!-- Cart Modal -->
<div id="cartDrawer" class="cart-drawer" v-cloak>
    <div class="cart-drawer-header d-flex justify-content-between align-items-center bg-primary text-white p-3 border-bottom">
        <h5 class="mb-0 font-weight-bold"><i class="fas fa-shopping-cart mr-2"></i> Shopping Cart</h5>
        <button onclick="toggleCartDrawer()" class="btn btn-sm btn-light text-primary rounded-circle p-2" style="width: 32px; height: 32px; display: flex; align-items: center; justify-content: center;">
            <i class="fas fa-times"></i>
        </button>
    </div>

    <div class="cart-drawer-body flex-grow-1 overflow-auto bg-white p-0">
        <div v-if="cartItems.length > 0">
            <ul class="list-group list-group-flush">
                <li class="list-group-item d-flex align-items-center py-3" v-for="(item, index) in cartItems" :key="index">
                    <div class="flex-grow-1">
                        <h6 class="mb-1 font-weight-bold">@{{ item.name }}</h6>
                        <div class="small text-muted mb-2">Point: @{{ item.trp }} | Price: @{{ item.price }}৳</div>
                        
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
                        <div class="font-weight-bold text-primary mb-3">@{{ item.price * item.quantity }}৳</div>
                        <button @click="removeItem(index)" class="btn btn-sm btn-light text-danger rounded-circle p-2 shadow-sm" style="width: 32px; height: 32px;">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                </li>
            </ul>
        </div>
        <div v-else class="text-center py-5 mt-5">
            <i class="fas fa-shopping-basket fa-4x text-muted mb-3 opacity-5"></i>
            <h5 class="text-muted font-weight-bold">Your cart is empty</h5>
            <p class="text-muted small mb-4">Looks like you haven't added any items yet.</p>
            <button onclick="toggleCartDrawer()" class="btn btn-primary rounded-pill px-5 py-2 shadow-sm">Start Shopping</button>
        </div>
    </div>

    <div v-if="cartItems.length > 0" class="cart-drawer-footer bg-light border-top p-4">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <span class="text-muted font-weight-bold text-uppercase">Total Amount:</span>
            <h3 class="text-primary font-weight-bold mb-0">@{{ cartTotal }}৳</h3>
        </div>
        <a href="{{ route('website.checkout') }}" class="btn btn-primary btn-block rounded-pill py-3 font-weight-bold mb-3 shadow">
            Proceed to Checkout <i class="fas fa-arrow-right ml-2"></i>
        </a>
        <button class="btn btn-link btn-block text-muted text-decoration-none small font-weight-bold" @click="clearCart()">
            <i class="fas fa-trash-alt mr-1"></i> Clear Entire Cart
        </button>
    </div>
</div>

<!-- Direct Order Modal -->
<div class="modal fade" id="directOrder" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg rounded-20 overflow-hidden">
            <div class="modal-header bg-primary text-white border-0 px-4 py-3">
                <h5 class="modal-title font-weight-bold">আপনার চাহিদা , আমাদের জানান!</h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body p-4">
                <form action="{{ route('directOrder') }}" method="post">
                    @csrf
                    <div class="form-group mb-4">
                        <label class="font-weight-bold small text-muted text-uppercase">পরিমান সহ পণ্যের নাম </label>
                        <textarea class="form-control rounded-lg border-2" rows="4" name="product_name" required
                            placeholder="যেমন: ১ কেজি লবন, ৫টি সাবান..."></textarea>
                    </div>

                    @guest
                        <div class="form-group mb-3">
                            <label class="font-weight-bold small text-muted text-uppercase">আপনার নাম</label>
                            <input type="text" name="username" class="form-control rounded-pill" required
                                placeholder="John Doe">
                        </div>
                        <div class="form-group mb-3">
                            <label class="font-weight-bold small text-muted text-uppercase">মোবাইল নাম্বার</label>
                            <input type="tel" name="phone" class="form-control rounded-pill" required
                                placeholder="017XXXXXXXX">
                        </div>
                        <div class="form-group mb-4">
                            <label class="font-weight-bold small text-muted text-uppercase">ডিলেভারি অ্যাড্রেস</label>
                            <input type="text" name="address" class="form-control rounded-pill" required
                                placeholder="আপনার পূর্ণ ঠিকানা">
                        </div>
                    @else
                        <input type="hidden" name="user_id" value="{{ Auth::user()->id }}">
                    @endguest

                    <button type="submit"
                        class="btn btn-primary btn-block rounded-pill py-3 shadow-sm font-weight-bold">
                        অর্ডার সাবমিট করুন <i class="fas fa-paper-plane ml-2"></i>
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>