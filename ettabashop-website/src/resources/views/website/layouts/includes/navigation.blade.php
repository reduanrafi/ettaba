<nav class="navbar navbar-expand-lg bg-light navbar-light py-3 py-lg-0 px-0 d-none d-lg-flex">
    <div class="collapse navbar-collapse justify-content-between" id="navbarCollapse">
        <div class="navbar-nav mr-auto py-0">
            <a href="{{ route('website.index') }}" class="nav-item nav-link active">{{__('menu.home')}}</a>
            <a href="{{ route('website.shop') }}" class="nav-item nav-link">{{__('menu.shop')}}</a>
            {{-- <a href="{{ route('website.about') }}" class="nav-item nav-link">{{__('menu.about')}}</a>--}}
            {{-- <div class="nav-item dropdown">--}}
                {{-- <a href="#" class="nav-link dropdown-toggle" data-toggle="dropdown">Pages</a>--}}
                {{-- <div class="dropdown-menu rounded-0 m-0">--}}
                    {{-- <a href="{{ route('website.index') }}" class="dropdown-item">Shopping Cart</a>--}}
                    {{-- <a href="{{ route('website.index') }}" class="dropdown-item">Checkout</a>--}}
                    {{-- </div>--}}
                {{-- </div>--}}
            {{-- <a href="{{ route('website.contact') }}" class="nav-item nav-link">{{__('menu.contact')}}</a>--}}
        </div>
        <div class="navbar-nav ml-auto py-0">

            @if(\Illuminate\Support\Facades\Auth::user())

                <a href="{{ route('user.profile') }}" class="nav-item nav-link">{{__('menu.profile')}}</a>
                <a href="{{ route('logout') }}" class="nav-item nav-link">{{__('menu.logout')}}</a>
            @else
                <a href="{{ route('login') }}" class="nav-item nav-link">{{__('menu.login')}}</a>
                <a href="{{ route('register') }}" class="nav-item nav-link">{{__('menu.register')}}</a>
            @endif

        </div>
        <div class="col-lg-0 p-0">

            <div class="d-block d-lg-none">
                @if(Request::is('/'))
                    @include('website.layouts.includes.sidebar')
                @endif
            </div>
        </div>
    </div>
</nav>