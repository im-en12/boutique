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
  
  <title>{{ $article->name }} - Furniture Store</title>
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
            <h1>Product Details</h1>
            <p class="mb-4">{{ $article->name }}</p>
          </div>
        </div>
      </div>
    </div>
  </div>
  <!-- End Hero Section -->

  <!-- Product Details -->
  <div class="untree_co-section">
    <div class="container">
      <div class="row">
        <!-- Product Image -->
        <div class="col-lg-6 mb-5">
          @if($article->image)
            <img src="{{ asset('storage/' . $article->image) }}" 
                 class="img-fluid rounded" 
                 alt="{{ $article->name }}"
                 style="max-height: 500px; object-fit: cover;">
          @else
            <img src="{{ asset('vendor/furni/images/product-3.png') }}" 
                 class="img-fluid rounded" 
                 alt="{{ $article->name }}">
          @endif
        </div>
        
        <!-- Product Info -->
        <div class="col-lg-6">
          <!-- Badges -->
          <div class="mb-3">
            @if($article->category)
              <span class="badge bg-secondary me-2">{{ $article->category->name }}</span>
            @endif
            @if($article->is_featured)
              <span class="badge bg-warning me-2">Featured</span>
            @endif
            @if($article->stock > 0)
              <span class="badge bg-success">In Stock ({{ $article->stock }})</span>
            @else
              <span class="badge bg-danger">Out of Stock</span>
            @endif
          </div>
          
          <h1 class="mb-3">{{ $article->name }}</h1>
          
          @if($article->excerpt)
            <p class="lead mb-4 text-muted">{{ $article->excerpt }}</p>
          @endif
          
          <!-- Price -->
          <h2 class="text-primary mb-4">${{ number_format($article->price, 2) }}</h2>
          
          <!-- Add to Cart Form -->
          @if($article->stock > 0)
          <div class="mb-5">
            <div class="row align-items-center">
              <div class="col-md-4 mb-3">
                <div class="input-group input-group-lg">
                  <button class="btn btn-outline-secondary" type="button" id="decrease-qty">-</button>
                  <input type="number" 
                         class="form-control text-center" 
                         id="quantity" 
                         value="1" 
                         min="1" 
                         max="{{ $article->stock }}">
                  <button class="btn btn-outline-secondary" type="button" id="increase-qty">+</button>
                </div>
              </div>
              <div class="col-md-8">
                <button class="btn btn-primary btn-lg w-100" id="add-to-cart-btn" 
                        data-product-slug="{{ $article->slug }}">
                  <i class="fas fa-shopping-cart me-2"></i> Add to Cart
                </button>
              </div>
            </div>
          </div>
          @endif
          
          <!-- Description -->
          @if($article->description)
          <div class="mb-5">
            <h4 class="mb-3">Description</h4>
            <div class="card">
              <div class="card-body">
                <p class="card-text">{{ $article->description }}</p>
              </div>
            </div>
          </div>
          @endif
          
          <!-- Back to Shop -->
          <a href="{{ route('shop') }}" class="btn btn-outline-secondary btn-lg">
            <i class="fas fa-arrow-left me-2"></i> Back to Shop
          </a>
        </div>
      </div>
      
      <!-- Related Products -->
      @if($relatedProducts && $relatedProducts->count() > 0)
      <div class="row mt-5">
        <div class="col-12">
          <h3 class="mb-4 border-bottom pb-3">Related Products</h3>
          <div class="row">
            @foreach($relatedProducts as $related)
            <div class="col-md-3 col-6 mb-4">
              <a href="{{ route('product.show', $related->slug) }}" class="product-item text-decoration-none">
                <div class="card h-100 border-0 shadow-sm">
                  @if($related->image)
                    <img src="{{ asset('storage/' . $related->image) }}" 
                         class="card-img-top" 
                         alt="{{ $related->name }}"
                         style="height: 200px; object-fit: cover;">
                  @else
                    <img src="{{ asset('vendor/furni/images/product-3.png') }}" 
                         class="card-img-top" 
                         alt="{{ $related->name }}">
                  @endif
                  <div class="card-body">
                    <h5 class="card-title">{{ Str::limit($related->name, 30) }}</h5>
                    <p class="card-text text-primary fw-bold">${{ number_format($related->price, 2) }}</p>
                  </div>
                </div>
              </a>
            </div>
            @endforeach
          </div>
        </div>
      </div>
      @endif
    </div>
  </div>

  <!-- Footer -->
  <footer class="footer-section">
    <div class="container relative">
      <!-- Your footer content -->
    </div>
  </footer>

  <!-- Scripts -->
  <script src="{{ asset('vendor/furni/js/bootstrap.bundle.min.js') }}"></script>
  
  <script>
  document.addEventListener('DOMContentLoaded', function() {
    const csrfToken = document.querySelector('meta[name="csrf-token"]').content;
    const quantityInput = document.getElementById('quantity');
    const addToCartBtn = document.getElementById('add-to-cart-btn');
    
    // Quantity controls
    document.getElementById('increase-qty')?.addEventListener('click', function() {
      if (quantityInput) {
        const max = parseInt(quantityInput.max);
        let value = parseInt(quantityInput.value);
        if (value < max) {
          quantityInput.value = value + 1;
        }
      }
    });
    
    document.getElementById('decrease-qty')?.addEventListener('click', function() {
      if (quantityInput) {
        let value = parseInt(quantityInput.value);
        if (value > 1) {
          quantityInput.value = value - 1;
        }
      }
    });
    
    // Quantity input validation
    quantityInput?.addEventListener('change', function() {
      let value = parseInt(this.value);
      const max = parseInt(this.max);
      const min = parseInt(this.min);
      
      if (value < min) value = min;
      if (value > max) value = max;
      this.value = value;
    });
    
    // Add to Cart functionality
    addToCartBtn?.addEventListener('click', function() {
      const productSlug = this.dataset.productSlug;
      const quantity = quantityInput ? parseInt(quantityInput.value) : 1;
      
      // Check if user is authenticated
      const isAuthenticated = {{ auth()->check() ? 'true' : 'false' }};
      
      if (!isAuthenticated) {
        // Redirect to login
        window.location.href = '{{ route("login") }}?redirect=' + encodeURIComponent(window.location.pathname);
        return;
      }
      
      // Add to cart via AJAX
      fetch('{{ route("cart.add") }}', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'X-CSRF-TOKEN': csrfToken,
          'Accept': 'application/json'
        },
        body: JSON.stringify({
          product_slug: productSlug,
          quantity: quantity
        })
      })
      .then(response => {
        if (response.status === 401) {
          // Session expired - redirect to login
          window.location.href = '{{ route("login") }}?redirect=' + encodeURIComponent(window.location.pathname);
          throw new Error('Unauthorized');
        }
        return response.json();
      })
      .then(data => {
        if (data.success) {
          // Show success message
          showNotification(data.message, 'success');
          // Update cart count
          updateCartCount(data.cart_count);
        } else {
          showNotification(data.message, 'error');
        }
      })
      .catch(error => {
        console.error('Error:', error);
        showNotification('An error occurred. Please try again.', 'error');
      });
    });
    
    function showNotification(message, type) {
      // Create notification element
      const notification = document.createElement('div');
      notification.className = `alert alert-${type} alert-dismissible fade show position-fixed`;
      notification.style.cssText = 'top: 20px; right: 20px; z-index: 9999; min-width: 300px;';
      notification.innerHTML = `
        ${message}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
      `;
      
      // Add to page
      document.body.appendChild(notification);
      
      // Auto remove after 3 seconds
      setTimeout(() => {
        notification.remove();
      }, 3000);
    }
    
    function updateCartCount(count) {
      const cartCountElement = document.getElementById('cart-count');
      if (cartCountElement) {
        cartCountElement.textContent = count;
        cartCountElement.style.display = count > 0 ? 'block' : 'none';
      }
    }
  });
  </script>
</body>
</html>