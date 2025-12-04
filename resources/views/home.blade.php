<!-- /*
* Bootstrap 5
* Template Name: Furni
* Template Author: Untree.co
* Template URI: https://untree.co/
* License: https://creativecommons.org/licenses/by/3.0/
*/ -->
<!DOCTYPE html>
<html lang="en"><head>
<meta http-equiv="content-type" content="text/html; charset=UTF-8">
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="author" content="Untree.co">
  <link rel="shortcut icon" href="public/vendor/furni/favicon.png">

  <meta name="description" content="">
  <meta name="keywords" content="bootstrap, bootstrap4">

		<!-- Bootstrap CSS -->
		<link href="{{ asset('vendor/furni/css/bootstrap.min.css') }}" rel="stylesheet">
		<link href="{{ asset('vendor/furni/css/all.min.css') }}" rel="stylesheet">
		<link href="{{ asset('vendor/furni/css/tiny-slider.css') }}" rel="stylesheet">
		<link href="{{ asset('vendor/furni/css/style.css') }}" rel="stylesheet">
		<title> Furniture and Interior Design  </title>
	<style></style></head>

	<body>


<!-- Start Header/Navigation --><nav class="custom-navbar navbar navbar navbar-expand-md navbar-dark bg-dark" arial-label="Furni navigation bar">

			<div class="container">
		<a class="navbar-brand" href="{{ url('/') }}">Furni<span>.</span></a>

				<button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarsFurni" aria-controls="navbarsFurni" aria-expanded="false" aria-label="Toggle navigation">
					<span class="navbar-toggler-icon"></span>
				</button>

				<div class="collapse navbar-collapse" id="navbarsFurni">
					<ul class="custom-navbar-nav navbar-nav ms-auto mb-2 mb-md-0">
						<li class="nav-item active">
							<a class="nav-link" href="public/vendor/furni/index.html">Home</a>
						</li>
						<li><a class="nav-link" href="{{ route('shop') }}">Shop</a></li>
						<li><a class="nav-link" href="#">About us</a></li>
						<li><a class="nav-link" href="#">Services</a></li>
						<li><a class="nav-link" href="#">Blog</a></li>
						<li><a class="nav-link" href="#">Contact us</a></li>
					</ul>

					  <ul class="custom-navbar-cta navbar-nav mb-2 mb-md-0 ms-5">
          @auth
            <!-- When logged in: Show user dropdown -->
            <li class="nav-item dropdown">
              <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                <img src="{{ asset('vendor/furni/images/user.svg') }}">
                <span class="d-none d-md-inline ms-1">{{ Auth::user()->name }}</span>
              </a>
              <ul class="dropdown-menu dropdown-menu-end">
                <!-- Use dashboard route -->
                <li><a class="dropdown-item" href="{{ route('dashboard') }}">Dashboard</a></li>
                <li><hr class="dropdown-divider"></li>
                <li>
                  <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="dropdown-item">Logout</button>
                  </form>
                </li>
              </ul>
            </li>
          @else
            <!-- When NOT logged in: Show login/register links -->
            <li class="nav-item">
              <a class="nav-link" href="{{ route('login') }}">Login</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="{{ route('register') }}">Register</a>
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
                @else
                  <span id="cart-count" class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" style="display: none;">0</span>
                @endif
              @endauth
            </a>
          </li>
        </ul>
				</div>
			</div>
				
		</nav><!-- End Header/Navigation --><!-- Start Hero Section --><div class="hero">
				<div class="container">
					<div class="row justify-content-between">
						<div class="col-lg-5">
							<div class="intro-excerpt">
								<h1>Modern Interior <span clsas="d-block">Design Studio</span></h1>
								<p class="mb-4">discover with us good quality with good price</p>
								<p><a href="{{ route('shop') }}" class="btn btn-secondary me-2">Shop Now</a><a href="#" class="btn btn-white-outline">Explore</a></p>
							</div>
						</div>
						<div class="col-lg-7">
							<div class="hero-img-wrap">
								<img src="vendor/furni/images/couch.png" class="img-fluid">
							</div>
						</div>
					</div>
				</div>
			</div><footer class="footer-section">
			<div class="container relative">

				

				<div class="row">
					<div class="col-lg-8">
						<div class="subscription-form">
							<h3 class="vendor/furni/images/envelope-outline.svg" alt="Image" class="img-fluid"></span><span>Subscribe to Newsletter</span></h3>

						

						</div>
					</div>
				</div>

				<div class="row g-5 mb-5">
					<div class="col-lg-4">
						<div class="mb-4 footer-logo-wrap"><a href="#" class="footer-logo">Furni<span>.</span></a></div>
						

						<ul class="list-unstyled custom-social">
							<li><a href="#"><span class="fa fa-brands fa-facebook-f"></span></a></li>
							<li><a href="#"><span class="fa fa-brands fa-twitter"></span></a></li>
							<li><a href="#"><span class="fa fa-brands fa-instagram"></span></a></li>
							<li><a href="#"><span class="fa fa-brands fa-linkedin"></span></a></li>
						</ul>
					</div>

					

				</div>

				

			</div>
		</footer>
		<!-- End Footer Section -->	


		<script src="{{ asset('vendor/furni/js/bootstrap.bundle.min.js') }}"></script>
		<script src="{{ asset('vendor/furni/js/tiny-slider.js') }}"></script>
		<script src="{{ asset('vendor/furni/js/custom.js') }}"></script>
	


</body></html>