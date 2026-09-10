<ul class="sidebar-menu" data-widget="tree">
    <li class="">
        <a href="{{ route('direct-seller.dashboard') }}">
            <i class="fa fa-dashboard"></i> <span>Dashboard</span>
        </a>
    </li>
    <li class="">
        <a href="{{ route('direct-seller.product.index') }}">
            <i class="fa fa-shopping-bag"></i> <span>My Products</span>
        </a>
    </li>
    <hr>
    <li class="">
        <a href="{{ route('direct-seller.order.create') }}">
            <i class="fa fa-cart-plus"></i> <span>Confirm Sale</span>
        </a>
    </li>
    <li class="">
        <a href="{{ route('direct-seller.order.index') }}">
            <i class="fa fa-list"></i> <span>Sales History</span>
        </a>
    </li>
    <li class="">
        <a href="{{ route('direct-seller.add_money.create') }}">
            <i class="fa fa-money"></i> <span>Add Money</span>
        </a>
    </li>
</ul>
