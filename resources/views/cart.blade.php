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
  
  <title>Cart - Furniture Store</title>
</head>

<body>

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
          <li><a class="nav-link" href="#">About us</a></li>
          <li><a class="nav-link" href="#">Services</a></li>
          <li><a class="nav-link" href="#">Blog</a></li>
          <li><a class="nav-link" href="#">Contact us</a></li>
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
               <li><a class="dropdown-item" href="{{ route('dashboard') }}">Dashboard</a></li>
                <li>
                  <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="dropdown-item">Logout</button>
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
                @else
                  <span id="cart-count" class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" style="display: none;">0</span>
                @endif
              @endauth
            </a>
          </li>
        </ul>
      </div>
    </div>
  </nav>
  <!-- End Header/Navigation -->

  <!-- Start Hero Section -->
  <div class="hero">
    <div class="container">
      <div class="row justify-content-between">
        <div class="col-lg-5">
          <div class="intro-excerpt">
            <h1>Cart</h1>
          </div>
        </div>
      </div>
    </div>
  </div>
  <!-- End Hero Section -->

  <div class="untree_co-section before-footer-section">
    <div class="container">
      @if($cartItems && $cartItems->count() > 0)
      <div class="row mb-5">
        <div class="col-md-12">
          <div class="site-blocks-table">
            <table class="table">
              <thead>
                <tr>
                  <th class="product-thumbnail">Image</th>
                  <th class="product-name">Product</th>
                  <th class="product-price">Price</th>
                  <th class="product-quantity">Quantity</th>
                  <th class="product-total">Total</th>
                  <th class="product-remove">Remove</th>
                </tr>
              </thead>
              <tbody>
                @php
                  $subtotal = 0;
                @endphp
                @foreach($cartItems as $item)
                @php
                  $itemTotal = $item->article->price * $item->quantity;
                  $subtotal += $itemTotal;
                @endphp
                <tr id="cart-item-{{ $item->id }}">
                  <td class="product-thumbnail">
                    @if($item->article->image)
                      <img src="{{ asset('storage/' . $item->article->image) }}" alt="{{ $item->article->name }}" class="img-fluid" style="width: 100px; height: 100px; object-fit: cover;">
                    @else
                      <img src="{{ asset('vendor/furni/images/product-1.png') }}" alt="{{ $item->article->name }}" class="img-fluid">
                    @endif
                  </td>
                  <td class="product-name">
                    <h2 class="h5 text-black">{{ $item->article->name }}</h2>
                    @if($item->article->excerpt)
                      <p class="text-muted small mb-0">{{ Str::limit($item->article->excerpt, 80) }}</p>
                    @endif
                  </td>
                  <td class="product-price">${{ number_format($item->article->price, 2) }}</td>
                  <td class="product-quantity">
                    <div class="input-group mb-3 d-flex align-items-center quantity-container" style="max-width: 120px;">
                      <div class="input-group-prepend">
                        <button class="btn btn-outline-black decrease" type="button" data-cart-id="{{ $item->id }}">&minus;</button>
                      </div>
                      <input type="text" class="form-control text-center quantity-amount" 
                             value="{{ $item->quantity }}" 
                             data-cart-id="{{ $item->id }}"
                             data-max="{{ $item->article->stock }}"
                             placeholder="" 
                             aria-label="Example text with button addon" 
                             aria-describedby="button-addon1">
                      <div class="input-group-append">
                        <button class="btn btn-outline-black increase" type="button" data-cart-id="{{ $item->id }}">&plus;</button>
                      </div>
                    </div>
                  </td>
                  <td class="product-total" id="item-total-{{ $item->id }}">
                    ${{ number_format($itemTotal, 2) }}
                  </td>
                  <td class="product-remove">
                    <button class="btn btn-black btn-sm remove-item" data-cart-id="{{ $item->id }}">X</button>
                  </td>
                </tr>
                @endforeach
              </tbody>
            </table>
          </div>
        </div>
      </div>

      <div class="row">
        <div class="col-md-6">
          <div class="row mb-5">
            <div class="col-md-6 mb-3 mb-md-0">
              <a href="{{ route('shop') }}" class="btn btn-outline-black btn-sm btn-block">Continue Shopping</a>
            </div>
            <div class="col-md-6">
              <button class="btn btn-black btn-sm btn-block" id="clear-cart">Clear Cart</button>
            </div>
          </div>
        </div>
        <div class="col-md-6 pl-5">
          <div class="row justify-content-end">
            <div class="col-md-7">
              <div class="row">
                <div class="col-md-12 text-right border-bottom mb-5">
                  <h3 class="text-black h4 text-uppercase">Cart Totals</h3>
                </div>
              </div>
              <div class="row mb-3">
                <div class="col-md-6">
                  <span class="text-black">Subtotal</span>
                </div>
                <div class="col-md-6 text-right">
                  <strong class="text-black" id="cart-subtotal">${{ number_format($subtotal, 2) }}</strong>
                </div>
              </div>
              <div class="row mb-3">
                <div class="col-md-6">
                  <span class="text-black">Tax (10%)</span>
                </div>
                <div class="col-md-6 text-right">
                  @php
                    $tax = $subtotal * 0.10;
                    $total = $subtotal + $tax;
                  @endphp
                  <strong class="text-black" id="cart-tax">${{ number_format($tax, 2) }}</strong>
                </div>
              </div>
              <div class="row mb-5">
                <div class="col-md-6">
                  <span class="text-black">Total</span>
                </div>
                <div class="col-md-6 text-right">
                  <strong class="text-black" id="cart-total">${{ number_format($total, 2) }}</strong>
                </div>
              </div>

              <div class="row">
                <div class="col-md-12">
                  <button class="btn btn-black btn-lg py-3 btn-block">Proceed To Checkout</button>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      @else
      <div class="row">
        <div class="col-12 text-center py-5">
          <h3 class="mb-4">Your cart is empty</h3>
          <p class="mb-4">Add some products to your cart and they will appear here.</p>
          <a href="{{ route('shop') }}" class="btn btn-primary">Start Shopping</a>
        </div>
      </div>
      @endif
    </div>
  </div>

  <!-- Start Footer Section -->
  <footer class="footer-section">
    <div class="container relative">
      <!-- Your existing footer code here -->
      <!-- ... -->
    </div>
  </footer>
  <!-- End Footer Section -->

  <!-- Scripts -->
  <script src="{{ asset('vendor/furni/js/bootstrap.bundle.min.js') }}"></script>
  <script src="{{ asset('vendor/furni/js/tiny-slider.js') }}"></script>
  <script src="{{ asset('vendor/furni/js/custom.js') }}"></script>
  
  <script>
  document.addEventListener('DOMContentLoaded', function() {
    const csrfToken = document.querySelector('meta[name="csrf-token"]').content;
    
    // Quantity increase
    document.querySelectorAll('.increase').forEach(button => {
      button.addEventListener('click', function() {
        const cartId = this.dataset.cartId;
        const input = document.querySelector(`.quantity-amount[data-cart-id="${cartId}"]`);
        const max = parseInt(input.dataset.max);
        let value = parseInt(input.value);
        
        if (value < max) {
          value++;
          input.value = value;
          updateCartItem(cartId, value);
        } else {
          showNotification('Maximum stock reached', 'warning');
        }
      });
    });
    
    // Quantity decrease
    document.querySelectorAll('.decrease').forEach(button => {
      button.addEventListener('click', function() {
        const cartId = this.dataset.cartId;
        const input = document.querySelector(`.quantity-amount[data-cart-id="${cartId}"]`);
        let value = parseInt(input.value);
        
        if (value > 1) {
          value--;
          input.value = value;
          updateCartItem(cartId, value);
        }
      });
    });
    
    // Manual quantity input
    document.querySelectorAll('.quantity-amount').forEach(input => {
      input.addEventListener('change', function() {
        const cartId = this.dataset.cartId;
        const max = parseInt(this.dataset.max);
        let value = parseInt(this.value);
        
        if (value < 1) value = 1;
        if (value > max) {
          value = max;
          this.value = max;
          showNotification('Maximum stock reached', 'warning');
        }
        
        updateCartItem(cartId, value);
      });
    });
    
    // Remove item
    document.querySelectorAll('.remove-item').forEach(button => {
      button.addEventListener('click', function() {
        const cartId = this.dataset.cartId;
        
        if (confirm('Are you sure you want to remove this item from cart?')) {
          removeCartItem(cartId);
        }
      });
    });
    
    // Clear cart
    document.getElementById('clear-cart')?.addEventListener('click', function() {
      if (confirm('Are you sure you want to clear your entire cart?')) {
        clearCart();
      }
    });
    
    // Update cart item
    function updateCartItem(cartId, quantity) {
      fetch(`/cart/${cartId}`, {
        method: 'PUT',
        headers: {
          'Content-Type': 'application/json',
          'X-CSRF-TOKEN': csrfToken,
          'Accept': 'application/json'
        },
        body: JSON.stringify({ quantity: quantity })
      })
      .then(response => response.json())
      .then(data => {
        if (data.success) {
          location.reload(); // Reload to update totals
        } else {
          showNotification(data.message, 'error');
        }
      })
      .catch(error => {
        console.error('Error:', error);
        showNotification('Failed to update cart', 'error');
      });
    }
    
    // Remove cart item
    function removeCartItem(cartId) {
      fetch(`/cart/${cartId}`, {
        method: 'DELETE',
        headers: {
          'X-CSRF-TOKEN': csrfToken,
          'Accept': 'application/json'
        }
      })
      .then(response => response.json())
      .then(data => {
        if (data.success) {
          location.reload();
        }
      })
      .catch(error => {
        console.error('Error:', error);
        showNotification('Failed to remove item', 'error');
      });
    }
    
    // Clear entire cart
    function clearCart() {
      fetch('/cart/clear', {
        method: 'POST',
        headers: {
          'X-CSRF-TOKEN': csrfToken,
          'Accept': 'application/json'
        }
      })
      .then(response => response.json())
      .then(data => {
        if (data.success) {
          location.reload();
        }
      });
    }
    
    // Show notification
    function showNotification(message, type) {
      const notification = document.createElement('div');
      notification.className = `alert alert-${type} alert-dismissible fade show position-fixed`;
      notification.style.cssText = 'top: 20px; right: 20px; z-index: 9999;';
      notification.innerHTML = `
        ${message}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
      `;
      
      document.body.appendChild(notification);
      
      setTimeout(() => {
        notification.remove();
      }, 3000);
    }
  });
  </script>
</body>
</html>