<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="author" content="Untree.co">
  <link rel="shortcut icon" href="{{ asset('vendor/furni/favicon.png') }}">

  <meta name="description" content="" />
  <meta name="keywords" content="bootstrap, bootstrap4" />

  <!-- Bootstrap CSS -->
  <link href="{{ asset('vendor/furni/css/bootstrap.min.css') }}" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
  <link href="{{ asset('vendor/furni/css/tiny-slider.css') }}" rel="stylesheet">
  <link href="{{ asset('vendor/furni/css/style.css') }}" rel="stylesheet">
  
  <!-- Price Range Slider CSS -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/noUiSlider/15.7.1/nouislider.min.css">
  
  <title>Shop - Furniture Store</title>
   <style>
    /* Heart icon styles */
    .product-heart {
      position: absolute;
      top: 10px;
      right: 10px;
      z-index: 10;
    }
    .product-heart button {
      background: rgba(255, 255, 255, 0.8);
      border-radius: 50%;
      width: 36px;
      height: 36px;
      display: flex;
      align-items: center;
      justify-content: center;
      transition: all 0.3s ease;
    }
    .product-heart button:hover {
      background: white;
      transform: scale(1.1);
    }
    .product-heart i {
      font-size: 1.2rem;
    }
    .product-item {
      position: relative;
      display: block;
    }
    .product-thumbnail {
      position: relative;
      overflow: hidden;
      border-radius: 8px;
    }
    .product-badges {
      position: absolute;
      top: 10px;
      left: 10px;
      z-index: 5;
    }
  </style>
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
            <h1>Shop</h1>
            <p class="mb-4">Discover our collection of premium furniture</p>
          </div>
        </div>
      </div>
    </div>
  </div>
  <!-- End Hero Section -->

  <!-- Start Filter Section -->
  <div class="container my-5">
    <div class="row">
      <div class="col-lg-12">
        <form method="GET" action="{{ route('shop') }}" id="filterForm" class="row g-3">
          
          <!-- Search Bar -->
          <div class="col-md-4">
            <div class="input-group">
              <input type="text" 
                     name="search" 
                     class="form-control" 
                     placeholder="Search products..." 
                     value="{{ request('search') }}">
              <button class="btn btn-outline-secondary" type="submit">
                <i class="fas fa-search"></i>
              </button>
            </div>
          </div>

          <!-- Category Filter -->
          <div class="col-md-3">
            <select name="category" class="form-control" onchange="this.form.submit()">
              <option value="">All Categories</option>
              @foreach($categories as $category)
              <option value="{{ $category->slug }}" 
                {{ request('category') == $category->slug ? 'selected' : '' }}>
                {{ $category->name }}
              </option>
              @endforeach
            </select>
          </div>

          <!-- Brand Filter -->
          <div class="col-md-3">
            <select name="brand" class="form-control" onchange="this.form.submit()">
              <option value="">All Brands</option>
              @foreach($brands as $brand)
              <option value="{{ $brand->slug }}" 
                {{ request('brand') == $brand->slug ? 'selected' : '' }}>
                {{ $brand->name }}
              </option>
              @endforeach
            </select>
          </div>

          <!-- Sort By -->
          <div class="col-md-2">
            <select name="sort" class="form-control" onchange="this.form.submit()">
              <option value="newest" {{ request('sort') == 'newest' ? 'selected' : '' }}>Newest</option>
              <option value="price_low" {{ request('sort') == 'price_low' ? 'selected' : '' }}>Price: Low to High</option>
              <option value="price_high" {{ request('sort') == 'price_high' ? 'selected' : '' }}>Price: High to Low</option>
              <option value="popular" {{ request('sort') == 'popular' ? 'selected' : '' }}>Most Popular</option>
              <option value="featured" {{ request('sort') == 'featured' ? 'selected' : '' }}>Featured</option>
            </select>
          </div>
          
          <!-- Price Range Filters (hidden inputs) -->
          <input type="hidden" name="min_price" id="minPriceInput" value="{{ request('min_price') }}">
          <input type="hidden" name="max_price" id="maxPriceInput" value="{{ request('max_price') }}">
          
          <!-- Clear Filters -->
          @if(request()->hasAny(['search', 'category', 'brand', 'min_price', 'max_price', 'sort']))
          <div class="col-md-12 mt-3">
            <a href="{{ route('shop') }}" class="btn btn-outline-danger btn-sm">
              <i class="fas fa-times"></i> Clear All Filters
            </a>
            
            @if(request('min_price') || request('max_price'))
            <span class="badge bg-info ms-2">
              Price: ${{ request('min_price', $minPrice) }} - ${{ request('max_price', $maxPrice) }}
            </span>
            @endif
          </div>
          @endif
        </form>
        
        <!-- Price Range Slider -->
        <div class="row mt-4">
          <div class="col-md-6">
            <div class="card">
              <div class="card-body">
                <h6 class="card-title">Price Range</h6>
                <div id="priceSlider" class="mt-3"></div>
                <div class="d-flex justify-content-between mt-2">
                  <span id="minPriceLabel">${{ $minPrice }}</span>
                  <span id="maxPriceLabel">${{ $maxPrice }}</span>
                </div>
                <button class="btn btn-primary btn-sm mt-3" onclick="applyPriceFilter()">
                  Apply Price Filter
                </button>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <!-- End Filter Section -->

  <!-- Start Products Section -->
  <div class="untree_co-section product-section before-footer-section">
    <div class="container">
      
      @if($articles->count() > 0)
        <div class="row">
          @foreach($articles as $article)
          <div class="col-12 col-md-4 col-lg-3 mb-5">
            <a class="product-item" href="{{ route('product.show', $article->slug) }}">
              @if($article->image)
                <img src="{{ asset('storage/' . $article->image) }}" 
                     class="img-fluid product-thumbnail" 
                     alt="{{ $article->name }}">
              @else
                <img src="{{ asset('vendor/furni/images/product-3.png') }}" 
                     class="img-fluid product-thumbnail" 
                     alt="{{ $article->name }}">
              @endif
              
              <h3 class="product-title">{{ $article->name }}</h3>
              
              @if($article->excerpt)
                <p class="product-excerpt text-muted small mb-2">
                  {{ Str::limit($article->excerpt, 60) }}
                </p>
              @endif
              <!-- HEART ICON - TOP RIGHT CORNER -->
                <div class="product-heart">
                  @auth
                    <form action="{{ route('dashboard.favorite.toggle', $article->id) }}" 
                          method="POST" 
                          class="d-inline favorite-toggle" 
                          data-article-id="{{ $article->id }}">
                      @csrf
                      <button type="submit" class="btn btn-link p-0 border-0">
                        @php
                          $isFavorite = Auth::user()->favorites()->where('article_id', $article->id)->exists();
                        @endphp
                        @if($isFavorite)
                          <i class="fas fa-heart text-danger"></i>
                        @else
                          <i class="far fa-heart text-dark"></i>
                        @endif
                      </button>
                    </form>
                  @else
                    <a href="{{ route('login') }}?redirect={{ urlencode(request()->fullUrl()) }}" 
                       class="btn btn-link p-0 border-0">
                      <i class="far fa-heart text-dark"></i>
                    </a>
                  @endauth
                </div>
              
              
              <strong class="product-price">${{ number_format($article->price, 2) }}</strong>
              
              <!-- Display category badge -->
              @if($article->category)
                <span class="badge bg-secondary">{{ $article->category->name }}</span>
              @endif
              
              <!-- Featured badge -->
              @if($article->is_featured)
                <span class="badge bg-warning">Featured</span>
              @endif
              
              <!-- Stock status -->
              @if($article->stock > 0)
                <span class="badge bg-success">In Stock</span>
              @else
                <span class="badge bg-danger">Out of Stock</span>
              @endif

              <span class="icon-cross">
                <img src="{{ asset('vendor/furni/images/cross.svg') }}" class="img-fluid">
              </span>
            </a>
          </div>
          @endforeach
        </div>
        
        <!-- Pagination -->
        @if($articles->hasPages())
        <div class="row mt-5">
          <div class="col-12">
            <nav aria-label="Page navigation">
              <ul class="pagination justify-content-center">
                {{-- Previous Page Link --}}
                @if ($articles->onFirstPage())
                  <li class="page-item disabled">
                    <span class="page-link">Previous</span>
                  </li>
                @else
                  <li class="page-item">
                    <a class="page-link" href="{{ $articles->previousPageUrl() }}" rel="prev">Previous</a>
                  </li>
                @endif

                {{-- Pagination Elements --}}
                @foreach ($articles->getUrlRange(1, $articles->lastPage()) as $page => $url)
                  @if ($page == $articles->currentPage())
                    <li class="page-item active">
                      <span class="page-link">{{ $page }}</span>
                    </li>
                  @else
                    <li class="page-item">
                      <a class="page-link" href="{{ $url }}">{{ $page }}</a>
                    </li>
                  @endif
                @endforeach

                {{-- Next Page Link --}}
                @if ($articles->hasMorePages())
                  <li class="page-item">
                    <a class="page-link" href="{{ $articles->nextPageUrl() }}" rel="next">Next</a>
                  </li>
                @else
                  <li class="page-item disabled">
                    <span class="page-link">Next</span>
                  </li>
                @endif
              </ul>
            </nav>
          </div>
        </div>
        @endif
        
      @else
        <div class="row">
          <div class="col-12 text-center">
            <div class="alert alert-info">
              <h4>No products found</h4>
              <p>Try changing your search criteria or check back later for new arrivals.</p>
              <a href="{{ route('shop') }}" class="btn btn-primary">Clear Filters</a>
            </div>
          </div>
        </div>
      @endif
      
    </div>
  </div>
  <!-- End Products Section -->

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
  
  <!-- Price Range Slider -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/noUiSlider/15.7.1/nouislider.min.js"></script>
  
  <script>
    // Initialize price range slider
    document.addEventListener('DOMContentLoaded', function() {
      const minPrice = {{ $minPrice }};
      const maxPrice = {{ $maxPrice }};
      const currentMin = {{ request('min_price', $minPrice) }};
      const currentMax = {{ request('max_price', $maxPrice) }};
      
      const priceSlider = document.getElementById('priceSlider');
      
      noUiSlider.create(priceSlider, {
        start: [currentMin, currentMax],
        connect: true,
        range: {
          'min': minPrice,
          'max': maxPrice
        },
        step: 1
      });
      
      const minPriceLabel = document.getElementById('minPriceLabel');
      const maxPriceLabel = document.getElementById('maxPriceLabel');
      const minPriceInput = document.getElementById('minPriceInput');
      const maxPriceInput = document.getElementById('maxPriceInput');
      
      priceSlider.noUiSlider.on('update', function(values) {
        const minVal = Math.round(values[0]);
        const maxVal = Math.round(values[1]);
        
        minPriceLabel.textContent = '$' + minVal;
        maxPriceLabel.textContent = '$' + maxVal;
        
        minPriceInput.value = minVal;
        maxPriceInput.value = maxVal;
      });
    });
    
    function applyPriceFilter() {
      document.getElementById('filterForm').submit();
    }
    
    // ADD TO CART FUNCTIONALITY WITH AUTH CHECK
      document.addEventListener('DOMContentLoaded', function() {
    const productItems = document.querySelectorAll('.product-item');
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    
    productItems.forEach(item => {
      // Handle click on the entire product card
      item.addEventListener('click', function(e) {
        const target = e.target;
        
        // Check if clicked on cross icon (add to cart)
        if (target.closest('.icon-cross') || 
            (target.closest('img') && target.closest('img').src.includes('cross.svg'))) {
          e.preventDefault();
          e.stopPropagation();
          
          // Get product slug from URL
          const productUrl = this.getAttribute('href');
          const productSlug = productUrl.split('/').pop();
          
          // Check if user is authenticated (Laravel auth check)
          const isAuthenticated = {{ auth()->check() ? 'true' : 'false' }};
          
          if (!isAuthenticated) {
            // Redirect to login with return URL
            const redirectUrl = '{{ route("login") }}?redirect=' + 
                              encodeURIComponent(window.location.pathname) +
                              '&add_to_cart=' + productSlug;
            window.location.href = redirectUrl;
            return;
          }
          
          // User is logged in - add to cart via AJAX
          addToCart(productSlug);
        }
        // Otherwise, let the link go to product details page
      });
    });
    
    function addToCart(productSlug) {
      fetch('{{ route("cart.add") }}', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'X-CSRF-TOKEN': csrfToken,
          'Accept': 'application/json'
        },
        body: JSON.stringify({
          product_slug: productSlug,
          quantity: 1
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
          // Show success notification
          showNotification(data.message, 'success');
          // Update cart count in navbar
          updateCartCount(data.cart_count);
        } else {
          showNotification(data.message, 'error');
        }
      })
      .catch(error => {
        console.error('Error:', error);
        showNotification('An error occurred. Please try again.', 'error');
      });
    }
    
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