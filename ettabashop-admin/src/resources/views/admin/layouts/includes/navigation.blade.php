<aside class="main-sidebar">
    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar">

        <!-- Sidebar user panel (optional) -->
        @if(\Illuminate\Support\Facades\Auth::user()->type == 'store_owner')
            @php
                $sellerShop = \App\Models\Shop::where('user_id', Auth::id())->first();
                $sellerLogo = (!empty($sellerShop) && !empty($sellerShop->logo_image)) 
                    ? asset($sellerShop->logo_image) 
                    : asset('assets/images/user.png');
                $sellerName = (!empty($sellerShop) && !empty($sellerShop->name_en)) 
                    ? $sellerShop->name_en 
                    : (Auth::user()->name ?? 'Seller');
                $sellerCreatedAt = Auth::user()->created_at ? Auth::user()->created_at->format('j-n-Y') : date('j-n-Y');
            @endphp
            <div class="user-panel" style="padding: 10px 8px; border-bottom: 1px solid #374151; background: rgba(0,0,0,0.15);">
                <div class="pull-left image" style="margin-right: 10px;">
                    <img src="{{ $sellerLogo }}" class="img-circle" alt="Seller Logo" style="width: 45px; height: 45px; object-fit: cover; border: 2px solid #22c55e;">
                </div>
                <div class="pull-left info" style="padding-left: 0; line-height: 1.35; position: static;">
                    <p style="margin-bottom: 3px; font-weight: 700; font-size: 13px; color: #fff;">
                        <i class="fa fa-circle text-success" style="font-size: 9px;"></i> {{ $sellerName }}
                    </p>
                    <div style="font-size: 11.5px; color: #94a3b8; font-family: monospace;">
                        <strong style="color: #38bdf8;">sID:15{{ Auth::user()->id }}</strong>
                    </div>
                    <div style="font-size: 10.5px; color: #cbd5e1; margin-top: 2px;">
                        Created: {{ $sellerCreatedAt }}
                    </div>
                </div>
                <div class="clearfix"></div>
            </div>
        @else
            <div class="user-panel">
                <div class="pull-left image">
                    <img src="{{asset('assets/images/user.png')}}" class="img-circle" alt="User Image">
                </div>
                <div class="pull-left info">
                    <p> <i class="fa fa-circle text-success"></i> {{ Auth::user()->name  }} </p>
                    <!-- Status -->
                    <a href="#">@if(\Illuminate\Support\Facades\Auth::user()->type == 'store_administrator')
                        <p
                            style="margin-top: 5px; font-weight: bold; color: #333; background: #fff; padding: 2px 5px; border-radius: 3px; display: inline-block;">
                            mID: 17{{ Auth::user()->id }}</p>
                    @endif
                    </a>

                </div>
            </div>
            <hr>
        @endif


        <!-- search form (Optional) -->
        {{--<form action="#" method="get" class="sidebar-form">--}}
            {{--<div class="input-group">--}}
                {{--<input type="text" name="q" class="form-control" placeholder="Search...">--}}
                {{--<span class="input-group-btn">--}}
                    {{--<button type="submit" name="search" id="search-btn" class="btn btn-flat"><i
                            class="fa fa-search"></i>--}}
                        {{--</button>--}}
                    {{--</span>--}}
                {{--</div>--}}
            {{--</form>--}}
        <!-- /.search form -->

        <!-- Sidebar Menu -->
        @if(\Illuminate\Support\Facades\Auth::user()->type == 'admin')
            @include('admin.layouts.includes.sidebars.admin')
        @elseif(\Illuminate\Support\Facades\Auth::user()->type == 'store_owner')
            @include('admin.layouts.includes.sidebars.shop')
        @elseif(\Illuminate\Support\Facades\Auth::user()->type == 'sales_staff')
            @include('admin.layouts.includes.sidebars.sales')
        @elseif(\Illuminate\Support\Facades\Auth::user()->type == 'store_administrator')
            @include('admin.layouts.includes.sidebars.handcash')
        @elseif(\Illuminate\Support\Facades\Auth::user()->type == 'direct_selling' || \Illuminate\Support\Facades\Auth::user()->customer_type == 'direct_selling')
            @include('admin.layouts.includes.sidebars.direct_seller')
        @endif
        <!-- /.sidebar-menu -->
    </section>
    <!-- /.sidebar -->

</aside>
<style>
    .sidebar-menu li a {
        color: #000;
    }
</style>