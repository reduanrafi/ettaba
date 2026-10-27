<ul class="sidebar-menu" data-widget="tree">

    <li class="">
        <a href="{{ route('dashboard.index') }}">
            <i class="fa fa-dashboard"></i> <span>Dashboard</span>
            <span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span>
        </a>
    </li>
    <li class="">
        <a href="#">
            <i class="fa fa-dashboard"></i> <span>Create Counter</span>
            <span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span>
        </a>
    </li>
    <li class="">
        <a href="{{ route('handcash.mywithdraws') }}">
            <i class="fa fa-bank"></i> <span>Withdraws</span>
            <span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span>
        </a>
    </li>
    <!-- <li class="">
        <a href="{{ route('handcash-category.index') }}">
            <i class="fa fa-book"></i> <span>Category</span>
            <span class="pull-right-container"> <i class="fa fa-angle-left pull-right"></i></span>
        </a>
    </li>
    <li class="">
        <a href="{{ route('handcash-product.index') }}">
            <i class="fa fa-book"></i> <span>Product</span>
            <span class="pull-right-container"> <i class="fa fa-angle-left pull-right"></i></span>
        </a>
    </li>
    <li class="">
        <a href="{{ route('product.deleted') }}">
            <i class="fa fa-book"></i> <span>Hidden Products</span>
            <span class="pull-right-container"> <i class="fa fa-angle-left pull-right"></i></span>
        </a>
    </li> -->
    <hr>
    <li class="">
        <a href="{{ route('handcash.sale.index') }}">
            <i class="fa fa-book"></i> <span>Manual Pay</span>
            <span class="pull-right-container"> <i class="fa fa-angle-left pull-right"></i></span>
        </a>
    </li>
    <li class="">
        <a href="{{ route('handcash.orders.index') }}">
            <i class="fa fa-book"></i> <span>Cashback History</span>
            <span class="pull-right-container"> <i class="fa fa-angle-left pull-right"></i></span>
        </a>
    </li>
    <li class="">
        <a href="{{ route('handcash.virtual_balance.create') }}">
            <i class="fa fa-money"></i> <span>Add Money</span>
            <span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span>
        </a>
    </li>
    <li class="">
        <a href="{{ route('handcash.mywithdraws') }}">
            <i class="fa fa-book"></i> <span>MyWithdraws</span>
            <span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span>
        </a>
    </li>
    <hr>
    <li class="{{ Request::is('my-rewards*') ? 'active' : '' }}">
        <a href="{{ route('handcash.rewards.index') }}">
            <i class="fa fa-trophy" style="color: #f39c12;"></i> <span>Merchant Rewards</span>
            <span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span>
        </a>
    </li>
    <li class="{{ Request::is('reward-history*') ? 'active' : '' }}">
        <a href="{{ route('handcash.rewards.history') }}">
            <i class="fa fa-medal" style="color: #3c8dbc;"></i> <span>Reward History</span>
            <span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span>
        </a>
    </li>
</ul>