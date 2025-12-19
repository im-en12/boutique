{{-- resources/views/partials/navigation.blade.php --}}
<!-- Start Header/Navigation -->
<nav class="custom-navbar navbar navbar navbar-expand-md navbar-dark bg-dark" arial-label="Furni navigation bar">
    <div class="container">
        <a class="navbar-brand" href="{{ url('/') }}">Furni<span>.</span></a>

        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarsFurni" aria-controls="navbarsFurni" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarsFurni">
            <ul class="custom-navbar-nav navbar-nav ms-auto mb-2 mb-md-0">
                <li class="nav-item {{ request()->is('/') ? 'active' : '' }}">
                    <a class="nav-link" href="{{ url('/') }}">Home</a>
                </li>
                <li class="nav-item {{ request()->is('shop') || request()->is('shop/*') ? 'active' : '' }}">
                    <a class="nav-link" href="{{ route('shop') }}">Shop</a>
                </li>
                
                {{-- SHOW ADMIN DASHBOARD LINK FOR ADMINS IN MAIN NAV --}}
                @auth
                    @if(auth()->user()->isAdmin())
                        <li class="nav-item {{ request()->is('admin*') ? 'active' : '' }}">
                            <a class="nav-link" href="{{ route('admin.dashboard') }}">
                                <i class="fas fa-crown me-1"></i>Admin Panel
                            </a>
                        </li>
                    @else
                        <li class="nav-item {{ request()->is('dashboard*') ? 'active' : '' }}">
                            <a class="nav-link" href="{{ route('dashboard') }}">Dashboard</a>
                        </li>
                    @endif
                @endauth
                
               
            </ul>

            <ul class="custom-navbar-cta navbar-nav mb-2 mb-md-0 ms-5">
                @auth
                    <!-- When logged in: Show user dropdown -->
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown">
                            <img src="{{ asset('vendor/furni/images/user.svg') }}">
                            <span class="d-none d-md-inline ms-1">{{ Auth::user()->name }}</span>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <!-- CHECK IF USER IS ADMIN -->
                            @if(auth()->user()->isAdmin())
                                {{-- ADMIN DASHBOARD LINK --}}
                                <li><a class="dropdown-item" href="{{ route('admin.dashboard') }}">
                                    <i class="fas fa-tachometer-alt me-2"></i>Admin Dashboard
                                </a></li>
                                <li><a class="dropdown-item" href="{{ route('admin.orders.index') }}">
                                    <i class="fas fa-shopping-bag me-2"></i>Manage Orders
                                </a></li>
                                <li><a class="dropdown-item" href="{{ route('admin.articles.index') }}">
                                    <i class="fas fa-box me-2"></i>Manage Products
                                </a></li>
                                <li><hr class="dropdown-divider"></li>
                            @else
                                {{-- REGULAR USER DASHBOARD LINK --}}
                                <li><a class="dropdown-item" href="{{ route('dashboard') }}">
                                    <i class="fas fa-tachometer-alt me-2"></i>My Dashboard
                                </a></li>
                            @endif
                            
                            {{-- COMMON LOGOUT FOR ALL USERS --}}
                            <li>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="dropdown-item">
                                        <i class="fas fa-sign-out-alt me-2"></i>Logout
                                    </button>
                                </form>
                            </li>
                        </ul>
                    </li>
                @else
                    <!-- When NOT logged in: Show login link -->
                    <li>
                        <a class="nav-link" href="{{ route('login') }}">
                            <img src="{{ asset('vendor/furni/images/user.svg') }}">
                        </a>
                    </li>
                @endauth
                
                <!-- Cart icon -->
                <li>
                    <a class="nav-link position-relative" href="{{ route('cart.view') }}">
                        <img src="{{ asset('vendor/furni/images/cart.svg') }}">
                        @auth
                            @php
                                $cartCount = App\Models\Cart::where('user_id', auth()->id())->count();
                            @endphp
                            @if($cartCount > 0)
                                <span id="cart-count" class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                                    {{ $cartCount }}
                                </span>
                            @endif
                        @endauth
                    </a>
                </li>
            </ul>
        </div>
    </div>
</nav>
<!-- End Header/Navigation -->
