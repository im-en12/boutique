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
    <link href="{{ asset('vendor/furni/css/tiny-slider.css') }}" rel="stylesheet">
    <link href="{{ asset('vendor/furni/css/style.css') }}" rel="stylesheet">
    
    <title>Checkout - Furniture Store</title>
</head>

<body>

    <!-- Start Header/Navigation -->
    @include('partials.navigation')

    <!-- End Header/Navigation -->

    <!-- Start Hero Section -->
    <div class="hero">
        <div class="container">
            <div class="row justify-content-between">
                <div class="col-lg-5">
                    <div class="intro-excerpt">
                        <h1>Checkout</h1>
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
                    <!-- Order Summary -->
                    <div class="card border-0 shadow-sm">
                        <div class="card-body">
                            <h2 class="h3 mb-4 text-black text-center">Order Summary</h2>
                            
                            <table class="table table-bordered mb-4">
                                <thead class="bg-light">
                                    <tr>
                                        <th>Product</th>
                                        <th class="text-end">Price</th>
                                        <th class="text-end">Qty</th>
                                        <th class="text-end">Total</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($cartItems as $item)
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                @if($item->article->image)
                                                    <img src="{{ asset('storage/' . $item->article->image) }}" 
                                                         alt="{{ $item->article->name }}" 
                                                         class="img-fluid me-3" 
                                                         style="width: 60px; height: 60px; object-fit: cover;">
                                                @else
                                                    <img src="{{ asset('vendor/furni/images/product-1.png') }}" 
                                                         alt="{{ $item->article->name }}" 
                                                         class="img-fluid me-3" 
                                                         style="width: 60px; height: 60px;">
                                                @endif
                                                <div>
                                                    <h6 class="mb-1">{{ $item->article->name }}</h6>
                                                    @if($item->article->excerpt)
                                                        <p class="text-muted small mb-0">{{ Str::limit($item->article->excerpt, 50) }}</p>
                                                    @endif
                                                </div>
                                            </div>
                                        </td>
                                        <td class="text-end">${{ number_format($item->article->price, 2) }}</td>
                                        <td class="text-end">{{ $item->quantity }}</td>
                                        <td class="text-end">${{ number_format($item->article->price * $item->quantity, 2) }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                                <tfoot class="bg-light">
                                    <tr>
                                        <td colspan="3" class="text-end"><strong>Subtotal:</strong></td>
                                        <td class="text-end">${{ number_format($subtotal, 2) }}</td>
                                    </tr>
                                    <tr>
                                        <td colspan="3" class="text-end"><strong>Tax (10%):</strong></td>
                                        <td class="text-end">${{ number_format($tax, 2) }}</td>
                                    </tr>
                                    <tr>
                                        <td colspan="3" class="text-end"><strong>Total:</strong></td>
                                        <td class="text-end"><strong>${{ number_format($total, 2) }}</strong></td>
                                    </tr>
                                </tfoot>
                            </table>

                            <!-- Simple Confirmation -->
                            <div class="alert alert-info mb-4">
                                <h5 class="alert-heading">Ready to place your order?</h5>
                                <p class="mb-0">Click "Place Order" to complete your purchase. Your cart will be cleared and stock will be updated.</p>
                            </div>

                            <div class="d-grid gap-2">
                                <button type="button" class="btn btn-success btn-lg py-3" id="place-order-btn">
                                    <i class="fas fa-shopping-bag me-2"></i> Place Order
                                </button>
                                
                                <a href="{{ route('cart.view') }}" class="btn btn-outline-secondary btn-lg py-3">
                                    <i class="fas fa-arrow-left me-2"></i> Back to Cart
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Start Footer Section -->
    <footer class="footer-section">
        <div class="container relative">
            <!-- Your existing footer code here -->
        </div>
    </footer>
    <!-- End Footer Section -->

    <!-- Scripts -->
    <script src="{{ asset('vendor/furni/js/bootstrap.bundle.min.js') }}"></script>
    
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const csrfToken = document.querySelector('meta[name="csrf-token"]').content;
        const placeOrderBtn = document.getElementById('place-order-btn');
        
        placeOrderBtn.addEventListener('click', function(e) {
            e.preventDefault();
            
            // Disable button and show loading
            this.disabled = true;
            const originalText = this.innerHTML;
            this.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i> Processing...';
            
            if (confirm('Confirm your order?')) {
                // Submit order via AJAX (no form data needed)
                fetch('{{ route("checkout.placeOrder") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({}) // Empty object since we don't need form data
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Show success message
                        alert(data.message);
                        // Redirect to confirmation page
                        if (data.redirect) {
                            window.location.href = data.redirect;
                        }
                    } else {
                        // Show error message
                        alert(data.message);
                        placeOrderBtn.disabled = false;
                        placeOrderBtn.innerHTML = originalText;
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('An error occurred. Please try again.');
                    placeOrderBtn.disabled = false;
                    placeOrderBtn.innerHTML = originalText;
                });
            } else {
                placeOrderBtn.disabled = false;
                placeOrderBtn.innerHTML = originalText;
            }
        });
    });
    </script>
</body>
</html>