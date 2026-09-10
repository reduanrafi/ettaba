<aside class="main-sidebar">
    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar">

        <!-- Sidebar user panel (optional) -->
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