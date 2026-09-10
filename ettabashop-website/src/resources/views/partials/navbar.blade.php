<nav class="navbar navbar-expand-md navbar-light navbar-custom sticky-top">
    <div class="container">
        <!-- Logo -->
        <a class="navbar-brand" href="{{ url('/') }}">
            {{ config('app.name', 'Shop') }}
        </a>

        <!-- Mobile Menu Button -->
        <button class="navbar-toggler border-0" type="button" onclick="toggleDrawer()">
            <span class="navbar-toggler-icon"></span>
        </button>

        <!-- Desktop Search -->
        <div class="d-none d-md-block flex-grow-1 mx-4">
            <div class="input-group">
                <div class="input-group-prepend">
                    <span class="input-group-text bg-light border-right-0"><i
                            class="fas fa-search text-muted"></i></span>
                </div>
                <input type="text" class="form-control bg-light border-left-0" placeholder="Search for products...">
            </div>
        </div>

        <!-- Desktop Menu -->
        <div class="collapse navbar-collapse flex-grow-0" id="navbarSupportedContent">
            <ul class="navbar-nav ml-auto align-items-center">
                <li class="nav-item mr-3">
                    <a href="#" class="nav-link text-center">
                        <i class="far fa-heart fa-lg d-block mb-1"></i>
                        <small>Wishlist</small>
                    </a>
                </li>
                <li class="nav-item mr-3 position-relative">
                    <a href="#" class="nav-link text-center">
                        <i class="fas fa-shopping-cart fa-lg d-block mb-1"></i>
                        <span class="badge badge-danger badge-pill position-absolute" style="top: 0; right: 0;">0</span>
                        <small>Cart</small>
                    </a>
                </li>

                @guest
                    <li class="nav-item">
                        <a href="{{ route('login') }}" class="nav-link font-weight-bold">Login</a>
                    </li>
                @else
                    <li class="nav-item dropdown">
                        <a id="navbarDropdown" class="nav-link dropdown-toggle font-weight-bold" href="#" role="button"
                            data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                            {{ Auth::user()->name }}
                        </a>

                        <div class="dropdown-menu dropdown-menu-right shadow-sm border-0" aria-labelledby="navbarDropdown">
                            <a class="dropdown-item" href="#">Profile</a>
                            <a class="dropdown-item" href="{{ route('logout') }}"
                                onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                Logout
                            </a>
                        </div>
                    </li>
                @endguest
            </ul>
        </div>
    </div>
</nav>