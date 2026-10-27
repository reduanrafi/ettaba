<ul class="sidebar-menu" data-widget="tree">
    <li>
        <a href="{{ route('website.index') }}">
            <i class="fa fa-list-alt"></i> <span>Visit website</span>
            <span class="pull-right-container">
                            <i class="fa fa-angle-left pull-right"></i>
                    </span>
        </a>


    </li>
    <li class="">
        <a href="{{ route('dashboard.index') }}">
            <i class="fa fa-dashboard"></i> <span>Dashboard</span>
            <span class="pull-right-container">
                            <i class="fa fa-angle-left pull-right"></i>
                        </span>
        </a>
    </li>

    <li class="">
        <a href="{{ route('slider.index') }}">
            <i class="fa fa-image"></i> <span>Slider</span>
            <span class="pull-right-container">
                            <i class="fa fa-angle-left pull-right"></i>
                        </span>
        </a>
    </li>
    <li class="">
        <a href="{{ route('brand.index') }}">
            <i class="fa fa-columns"></i> <span>Brand</span>
            <span class="pull-right-container">
                            <i class="fa fa-angle-left pull-right"></i>
                        </span>
        </a>
    </li>

    <li class="">
        <a href="{{ route('category.index') }}">
            <i class="fa fa-columns"></i> <span>Category</span>
            <span class="pull-right-container">
                            <i class="fa fa-angle-left pull-right"></i>
                        </span>
        </a>
    </li>
    <li class="">
        <a href="{{ route('product.index') }}">
            <i class="fa fa-book"></i> <span>Product</span>
            <span class="pull-right-container">
                            <i class="fa fa-angle-left pull-right"></i>
                        </span>
        </a>
    </li>
    <li class="">
        <a href="{{ route('product.deleted') }}">
            <i class="fa fa-book"></i> <span>Hidden Products</span>
            <span class="pull-right-container"> <i class="fa fa-angle-left pull-right"></i></span>
        </a>
    </li>
    <li class="">
        <a href="{{ route('topProduct.index') }}">
            <i class="fa fa-book"></i> <span>Top product</span>
            <span class="pull-right-container">
                            <i class="fa fa-angle-left pull-right"></i>
                        </span>
        </a>
    </li>
    <li class="">
        <a href="{{ route('order.index') }}">
            <i class="fa fa-shopping-cart"></i> <span>Orders</span>
            <span class="pull-right-container">
                            <i class="fa fa-angle-left pull-right"></i>
                        </span>
        </a>
    </li>
    <li class="">
        <a href="{{ route('merchant.history') }}">
            <i class="fa fa-history"></i> <span>Merchants History</span>
            <span class="pull-right-container">
                            <i class="fa fa-angle-left pull-right"></i>
                        </span>
        </a>
    </li>
    <li class="">
        <a href="{{ route('anonymousOrder.index') }}">
            <i class="fa fa-shopping-cart"></i> <span>Anonymous Orders</span>
            <span class="pull-right-container">
                            <i class="fa fa-angle-left pull-right"></i>
                        </span>
        </a>
    </li>

    <li class="">
        <a href="{{ route('dOrder.index') }}">
            <i class="fa fa-shopping-cart"></i> <span>Customer's need</span>
            <span class="pull-right-container">
                            <i class="fa fa-angle-left pull-right"></i>
                        </span>
        </a>
    </li>
    <hr>
    <li class="">
        <a href="{{ route('customer.index') }}">
            <i class="fa fa-user"></i> <span>Customers</span>
            <span class="pull-right-container">
                            <i class="fa fa-angle-left pull-right"></i>
                        </span>
        </a>
    </li>

    <li class="">
        <a href="{{ route('store.index') }}">
            <i class="fa fa-shopping-basket"></i> <span>Shops</span>
            <span class="pull-right-container">
                            <i class="fa fa-angle-left pull-right"></i>
                        </span>
        </a>
    </li>
    <li class="">
        <a href="{{ route('merchant.index') }}">
            <i class="fa fa-users"></i> <span>Merchants</span>
            <span class="pull-right-container">
                            <i class="fa fa-angle-left pull-right"></i>
                        </span>
        </a>
    </li>
    <hr>
    <li class="">
        <a href="{{ route('withdrawRequest.index') }}">
            <i class="fa fa-1x">৳</i> <span>Withdraw Requests</span>
            <span class="pull-right-container">
                            <i class="fa fa-angle-left pull-right"></i>
                        </span>
        </a>
    </li>
    <li class="">
        <a href="{{ route('withdrawRequest.histories') }}">
            <i class="fa fa-1x">৳</i> <span>Withdraw histories</span>
            <span class="pull-right-container">
                            <i class="fa fa-angle-left pull-right"></i>
                        </span>
        </a>
    </li>
    <hr>
    <li class="">
        <a href="{{ route('bonus.index') }}">
            <i class="fa fa-1x">৳</i> <span>Distribute bonuses</span>
            <span class="pull-right-container">
                            <i class="fa fa-angle-left pull-right"></i>
                        </span>
        </a>
    </li>
    <li class="">
        <a href="{{ route('vb.index') }}">
            <i class="fa fa-1x">৳</i> <span>Virtual Balance</span>
            <span class="pull-right-container">
                            <i class="fa fa-angle-left pull-right"></i>
                        </span>
        </a>
    </li>
    <hr>
    <li class="treeview {{ Request::is('private-panel/geo*') ? 'active' : '' }}">
        <a href="#">
            <i class="fa fa-map-marker"></i> <span>Geo / ঠিকানা সেটআপ</span>
            <span class="pull-right-container">
                <i class="fa fa-angle-left pull-right"></i>
            </span>
        </a>
        <ul class="treeview-menu">
            <li><a href="{{ route('geo.upazilas.index') }}"><i class="fa fa-building-o"></i> উপজেলা / সিটি কর্পোরেশন</a></li>
            <li><a href="{{ route('geo.unions.index') }}"><i class="fa fa-map-signs"></i> পৌরসভা / ইউনিয়ন / ওয়ার্ড</a></li>
        </ul>
    </li>

    <hr>
    <li class="treeview {{ Request::is('private-panel/rewards*') || Request::is('private-panel/reward-applications*') || Request::is('private-panel/online-categories*') ? 'active' : '' }}">
        <a href="#">
            <i class="fa fa-trophy" style="color: #f39c12;"></i> <span>Reward Management</span>
            <span class="pull-right-container">
                <i class="fa fa-angle-left pull-right"></i>
            </span>
        </a>
        <ul class="treeview-menu">
            <li><a href="{{ route('admin.rewards.index', 'customer') }}"><i class="fa fa-users"></i> 1. Customer Rewards</a></li>
            <li><a href="{{ route('admin.rewards.index', 'partner') }}"><i class="fa fa-handshake-o"></i> 2. Partner Rewards</a></li>
            <li><a href="{{ route('admin.rewards.index', 'merchant') }}"><i class="fa fa-shopping-bag"></i> 3. Merchant Rewards</a></li>
            <li><a href="{{ route('admin.rewardApplications.index') }}"><i class="fa fa-envelope-open-o"></i> 4. Reward Applications</a></li>
            <li><a href="{{ route('admin.rewardApplications.history') }}"><i class="fa fa-history"></i> 5. Application History</a></li>
            <li><a href="{{ route('admin.onlineCategories.index') }}"><i class="fa fa-th-large"></i> Online Categories</a></li>
            <li><a href="{{ route('admin.rewards.settings') }}"><i class="fa fa-cogs"></i> Reward Settings</a></li>
        </ul>
    </li>
</ul>
