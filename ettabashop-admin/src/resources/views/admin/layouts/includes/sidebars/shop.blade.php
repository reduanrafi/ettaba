@php
    $unseenSellerOrders = 0;
    try {
        $unseenSellerOrders = \App\Http\Controllers\Shopper\SellerOrderController::getUnseenOrderCountForSeller(\Illuminate\Support\Facades\Auth::id());
    } catch (\Throwable $e) {
        $unseenSellerOrders = 0;
    }
@endphp
<ul class="sidebar-menu" data-widget="tree">
    <li class="{{ Request::is('private-panel/index*') ? 'active' : '' }}">
        <a href="{{ route('dashboard.index') }}">
            <i class="fa fa-dashboard"></i> <span>Dashboard</span>
            <span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span>
        </a>
    </li>
    <li class="{{ request()->routeIs('seller.orders.*') ? 'active' : '' }}">
        <a href="{{ route('seller.orders.index') }}">
            <i class="fa fa-shopping-bag text-green"></i> <span>My Orders</span>
            <span class="pull-right-container">
                @if($unseenSellerOrders > 0)
                    <small class="label pull-right bg-red">{{ $unseenSellerOrders }} New</small>
                @else
                    <i class="fa fa-angle-left pull-right"></i>
                @endif
            </span>
        </a>
    </li>
    <li class="{{ Request::is('private-panel/product/create*') ? 'active' : '' }}">
        <a href="{{ route('product.create') }}">
            <i class="fa fa-plus-circle text-primary"></i> <span>Add Product</span>
            <span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span>
        </a>
    </li>
    <li class="{{ Request::is('private-panel/product/deleted*') ? 'active' : '' }}">
        <a href="{{ route('product.deleted') }}">
            <i class="fa fa-eye-slash text-muted"></i> <span>Hidden Product</span>
            <span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span>
        </a>
    </li>
    <li class="{{ Request::is('private-panel/shop*') ? 'active' : '' }}">
        <a href="{{ route('shop.index') }}">
            <i class="fa fa-home text-yellow"></i> <span>Shop Profile</span>
            <span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span>
        </a>
    </li>
    <li class="{{ Request::is('private-panel/seller/add-money*') ? 'active' : '' }}">
        <a href="{{ route('seller.addMoney.index') }}">
            <i class="fa fa-plus-square text-green"></i> <span>Add Money</span>
            <span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span>
        </a>
    </li>
    <li class="{{ Request::is('private-panel/mywithdraws*') ? 'active' : '' }}">
        <a href="{{ route('mywithdraws') }}">
            <i class="fa fa-money text-aqua"></i> <span>My Withdraws</span>
            <span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span>
        </a>
    </li>
</ul>
