<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="author" content="Untree.co">
    <link rel="shortcut icon" href="{{ asset('vendor/furni/favicon.png') }}">

    <meta name="description" content="" />
    <meta name="keywords" content="bootstrap, bootstrap4" />
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Bootstrap CSS -->
    <link href="{{ asset('vendor/furni/css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <link href="{{ asset('vendor/furni/css/style.css') }}" rel="stylesheet">
    
    <title>Order Confirmation - Furniture Store</title>
</head>

<body>

    <!-- Start Header/Navigation -->
    <!-- Same navigation as checkout.blade.php -->
    <!-- Copy the nav from checkout.blade.php -->

    <!-- Start Hero Section -->
    <div class="hero">
        <div class="container">
            <div class="row justify-content-between">
                <div class="col-lg-5">
                    <div class="intro-excerpt">
                        <h1>Order Confirmation</h1>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- End Hero Section -->

    <div class="untree_co-section">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-8">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body text-center p-5">
                            <div class="mb-4">
                                <i class="fas fa-check-circle text-success fa-5x mb-4"></i>
                                <h2 class="mb-3">Thank You for Your Order!</h2>
                                <p class="lead">Your order has been placed successfully.</p>
                            </div>
                            
                            <div class="card mb-4">
                                <div class="card-body">
                                    <h4 class="card-title">Order Details</h4>
                                    <div class="row text-start">
                                        <div class="col-md-6">
                                            <p><strong>Order ID:</strong> #{{ $order->id }}</p>
                                            <p><strong>Order Date:</strong> {{ $order->created_at->format('F d, Y') }}</p>
                                            <p><strong>Status:</strong> 
                                                <span class="badge bg-info">{{ ucfirst($order->status) }}</span>
                                            </p>
                                        </div>
                                        <div class="col-md-6">
                                            <p><strong>Total Amount:</strong> ${{ number_format($order->total_price, 2) }}</p>
                                        </div>
                                    </div>
                                    
                                    <!-- Order Items -->
                                    <h5 class="mt-4 mb-3">Order Items:</h5>
                                    <ul class="list-group list-group-flush">
                                        @foreach($order->items as $item)
                                        <li class="list-group-item d-flex justify-content-between">
                                            <span>{{ $item->article->name }} x {{ $item->quantity }}</span>
                                            <span>${{ number_format($item->unit_price * $item->quantity, 2) }}</span>
                                        </li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                            
                            <div class="d-grid gap-2 d-md-flex justify-content-center">
                                <a href="{{ route('shop') }}" class="btn btn-primary me-md-2">
                                    <i class="fas fa-shopping-bag me-2"></i> Continue Shopping
                                </a>
                                <a href="{{ route('dashboard') }}" class="btn btn-outline-secondary">
                                    <i class="fas fa-user me-2"></i> View Dashboard
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <!-- Same footer as checkout.blade.php -->

    <!-- Scripts -->
    <script src="{{ asset('vendor/furni/js/bootstrap.bundle.min.js') }}"></script>
</body>
</html>