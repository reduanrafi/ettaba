@php
    $unseenSellerOrders = 0;
    try {
        $unseenSellerOrders = \App\Http\Controllers\Shopper\SellerOrderController::getUnseenOrderCountForSeller(\Illuminate\Support\Facades\Auth::id());
    } catch (\Throwable $e) {
        $unseenSellerOrders = 0;
    }
@endphp
<ul class="sidebar-menu" data-widget="tree">
    <li>
        <a href="{{ route('website.index') }}">
            <i class="fa fa-list-alt"></i> <span>Visit website</span>
            <span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span>
        </a>
    </li>
    <li>
        <a href="{{ route('dashboard.index') }}">
            <i class="fa fa-dashboard"></i> <span>Dashboard</span>
            <span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span>
        </a>
    </li>
    <li class="{{ request()->routeIs('seller.orders.*') ? 'active' : '' }}">
        <a href="{{ route('seller.orders.index') }}">
            <i class="fa fa-shopping-bag text-green"></i> <span>My Orders (অর্ডারসমূহ)</span>
            <span class="pull-right-container">
                @if($unseenSellerOrders > 0)
                    <small class="label pull-right bg-red">{{ $unseenSellerOrders }} New</small>
                @else
                    <i class="fa fa-angle-left pull-right"></i>
                @endif
            </span>
        </a>
    </li>
    <li>
        <a href="{{ route('product.index') }}">
            <i class="fa fa-book"></i> <span>Product</span>
            <span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span>
        </a>
    </li>
    <li>
        <a href="{{ route('product.deleted') }}">
            <i class="fa fa-book"></i> <span>Hidden Products</span>
            <span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span>
        </a>
    </li>
    <li>
        <a href="{{ route('shop.index') }}">
            <i class="fa fa-book"></i> <span>Shop</span>
            <span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span>
        </a>
    </li>
    <li>
        <a href="{{ route('mywithdraws') }}">
            <i class="fa fa-book"></i> <span>MyWithdraws</span>
            <span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span>
        </a>
    </li>
</ul>
