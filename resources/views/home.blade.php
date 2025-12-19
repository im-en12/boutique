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


<!-- Start Header/Navigation -->
 @include('partials.navigation')
<!-- End Header/Navigation -->
 <!-- Start Hero Section --><div class="hero">
				<div class="container">
					<div class="row justify-content-between">
						<div class="col-lg-5">
							<div class="intro-excerpt">
								<h1>Modern Interior <span clsas="d-block">Design Studio</span></h1>
								<p class="mb-4">discover with us good quality with good price</p>
								<p><a href="{{ route('shop') }}" class="btn btn-secondary me-2">Shop Now</a>
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
							<li><a href="#"><span><img src="vendor/furni/images/facebook-f-brands-solid-full.svg" class="fa fa-brands fa-facebook-f"></span></a></li>
							<li><a href="#"><span><img src="vendor/furni/images/twitter-brands-solid-full.svg" class="fa fa-brands fa-twitter"></span></a></li>
							<li><a href="#"><span><img src="vendor/furni/images/instagram-brands-solid-full.svg" class="fa fa-brands fa-instagram"></span></a></li>
							<li><a href="#"><span><img src="vendor/furni/images/linkedin-brands-solid-full.svg" class="fa fa-brands fa-linkedin"></span></a></li>
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