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
  
  <title>My Dashboard - Boutique</title>
  
  <style>
    .dashboard-card {
      border: none;
      border-radius: 10px;
      box-shadow: 0 4px 6px rgba(0,0,0,0.1);
      margin-bottom: 20px;
    }
    .stat-card {
      background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
      color: white;
      border-radius: 10px;
      padding: 15px;
      text-align: center;
      margin-bottom: 15px;
    }
    .sidebar-link {
      display: block;
      padding: 10px 15px;
      color: #333;
      text-decoration: none;
      border-radius: 5px;
      margin-bottom: 5px;
    }
    .sidebar-link:hover, .sidebar-link.active {
      background: #2f2f2f;
      color: white;
    }
    .rating-stars {
      color: #FFD700;
    }
    .order-status-badge {
      font-size: 0.8rem;
      padding: 3px 10px;
      border-radius: 15px;
    }
    .product-thumb {
      width: 80px;
      height: 80px;
      object-fit: cover;
      border-radius: 8px;
    }
    .modal-rating label {
      font-size: 30px;
      color: #ddd;
      cursor: pointer;
    }
    .modal-rating input:checked ~ label,
    .modal-rating label:hover,
    .modal-rating label:hover ~ label {
      color: #FFD700;
    }
  </style>
</head>

<body>

  <!-- Header/Navigation -->
  <nav class="custom-navbar navbar navbar navbar-expand-md navbar-dark bg-dark" arial-label="Furni navigation bar">
    <div class="container">
      <a class="navbar-brand" href="{{ url('/') }}">Furni<span>.</span></a>

      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarsFurni" aria-controls="navbarsFurni" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>

      <div class="collapse navbar-collapse" id="navbarsFurni">
        <ul class="custom-navbar-nav navbar-nav ms-auto mb-2 mb-md-0">
          <li class="nav-item">
            <a class="nav-link" href="{{ url('/') }}">Home</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="{{ route('shop') }}">Shop</a>
          </li>
          <li class="nav-item active">
            <a class="nav-link" href="{{ route('dashboard') }}">Dashboard</a>
          </li>
        </ul>

        <ul class="custom-navbar-cta navbar-nav mb-2 mb-md-0 ms-5">
          @auth
            <li class="nav-item dropdown">
              <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown">
                <img src="{{ asset('vendor/furni/images/user.svg') }}">
                <span class="d-none d-md-inline ms-1">{{ Auth::user()->name }}</span>
              </a>
              <ul class="dropdown-menu dropdown-menu-end">
                <li><a class="dropdown-item" href="{{ route('dashboard') }}"><i class="fas fa-tachometer-alt me-2"></i>Dashboard</a></li>
                <li><hr class="dropdown-divider"></li>
                <li>
                  <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="dropdown-item"><i class="fas fa-sign-out-alt me-2"></i>Logout</button>
                  </form>
                </li>
              </ul>
            </li>
          @endauth
          
          <li>
            <a class="nav-link position-relative" href="{{ route('cart.view') }}">
              <img src="{{ asset('vendor/furni/images/cart.svg') }}">
              @auth
                @php
                  $cartCount = App\Models\Cart::where('user_id', auth()->id())->count();
                @endphp
                @if($cartCount > 0)
                  <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
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

  <!-- Hero Section -->
  <div class="hero">
    <div class="container">
      <div class="row justify-content-between">
        <div class="col-lg-5">
          <div class="intro-excerpt">
            <h1>My Dashboard</h1>
            <p class="mb-4">Welcome back, {{ Auth::user()->name }}!</p>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="untree_co-section">
    <div class="container">
      <div class="row">
        <!-- Sidebar -->
        <div class="col-lg-3 mb-5">
          <div class="dashboard-card p-4">
            <h5 class="mb-4"><i class="fas fa-user-circle me-2"></i>My Account</h5>
            <a href="{{ route('dashboard') }}" class="sidebar-link {{ $section === 'dashboard' ? 'active' : '' }}">
              <i class="fas fa-tachometer-alt me-2"></i>Dashboard
            </a>
            <a href="{{ route('dashboard') }}?section=orders" class="sidebar-link {{ $section === 'orders' ? 'active' : '' }}">
              <i class="fas fa-shopping-bag me-2"></i>My Orders
            </a>
            <a href="{{ route('dashboard') }}?section=favorites" class="sidebar-link {{ $section === 'favorites' ? 'active' : '' }}">
              <i class="fas fa-heart me-2"></i>My Favorites
            </a>
            <a href="{{ route('dashboard') }}?section=reviews" class="sidebar-link {{ $section === 'reviews' ? 'active' : '' }}">
              <i class="fas fa-star me-2"></i>My Reviews
            </a>
            <hr class="my-3">
            <a href="{{ route('shop') }}" class="sidebar-link text-primary">
              <i class="fas fa-store me-2"></i>Continue Shopping
            </a>
          </div>
          
          <!-- Stats -->
          <div class="stat-card">
            <h3 class="text-white">{{ $stats['total_orders'] }}</h3>
            <p class="text-white mb-0">Total Orders</p>
          </div>
          <div class="stat-card" style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);">
            <h3 class="text-white">{{ $stats['total_favorites'] }}</h3>
            <p class="text-white mb-0">Favorites</p>
          </div>
          <div class="stat-card" style="background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);">
            <h3 class="text-white">{{ $stats['total_reviews'] }}</h3>
            <p class="text-white mb-0">Reviews</p>
          </div>
        </div>
        
        <!-- Main Content -->
        <div class="col-lg-9">
          
          @if($section === 'dashboard')
          <!-- DASHBOARD SUMMARY -->
          
            <!-- Recent Orders -->
            <div class="dashboard-card">
              <div class="card-header bg-dark text-white">
                <h5 class="mb-0"><i class="fas fa-clock me-2"></i>Recent Orders</h5>
              </div>
              <div class="card-body">
                @if(isset($orders) && $orders->count() > 0)
                  @foreach($orders as $order)
                  <div class="border-bottom pb-3 mb-3">
                    <div class="d-flex justify-content-between align-items-center">
                      <div>
                        <h6 class="mb-1">Order #{{ $order->id }}</h6>
                        <p class="mb-1 small">{{ $order->created_at->format('M d, Y') }} • ${{ number_format($order->total_price, 2) }}</p>
                        <span class="order-status-badge badge bg-{{ 
                          $order->status === 'pending' ? 'warning' : 
                          ($order->status === 'paid' ? 'info' : 
                          ($order->status === 'shipped' ? 'primary' : 
                          ($order->status === 'delivered' ? 'success' : 'secondary')))
                        }}">
                          {{ ucfirst($order->status) }}
                        </span>
                      </div>
                      <div>
                        <a href="{{ route('order.confirmation', $order->id) }}" class="btn btn-sm btn-outline-dark me-1">
                          <i class="fas fa-eye"></i>
                        </a>
                        @php
                          $hoursSinceOrder = now()->diffInHours($order->created_at);
                          $canCancel = $order->status === 'pending' && $hoursSinceOrder <= 24 && $order->status !== 'cancelled';
                        @endphp
                        @if($canCancel)
                          <form action="{{ route('dashboard.order.cancel', $order->id) }}" method="POST" class="d-inline">
                            @csrf
                            <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('Cancel order #{{ $order->id }}?')">
                              <i class="fas fa-times"></i>
                            </button>
                          </form>
                        @endif
                      </div>
                    </div>
                  </div>
                  @endforeach
                  <div class="text-center">
                    <a href="{{ route('dashboard') }}?section=orders" class="btn btn-dark">View All Orders</a>
                  </div>
                @else
                  <div class="text-center py-4">
                    <i class="fas fa-shopping-bag fa-3x text-muted mb-3"></i>
                    <p class="text-muted">No orders yet</p>
                    <a href="{{ route('shop') }}" class="btn btn-primary">Start Shopping</a>
                  </div>
                @endif
              </div>
            </div>
            
            <!-- Favorites & Reviews Row -->
            <div class="row">
              <div class="col-md-6">
                <div class="dashboard-card h-100">
                  <div class="card-header bg-dark text-white">
                    <h5 class="mb-0"><i class="fas fa-heart me-2"></i>My Favorites</h5>
                  </div>
                  <div class="card-body">
                    @if(isset($favorites) && $favorites->count() > 0)
                      <div class="row">
                        @foreach($favorites as $favorite)
                        <div class="col-6 mb-3">
                          <img src="{{ $favorite->article->image ? asset('storage/' . $favorite->article->image) : asset('vendor/furni/images/product-1.png') }}" 
                               class="product-thumb w-100 mb-2" alt="{{ $favorite->article->name }}">
                          <h6 class="small">{{ Str::limit($favorite->article->name, 20) }}</h6>
                          <p class="text-primary mb-2">${{ number_format($favorite->article->price, 2) }}</p>
                        </div>
                        @endforeach
                      </div>
                      <div class="text-center">
                        <a href="{{ route('dashboard') }}?section=favorites" class="btn btn-outline-dark btn-sm">View All</a>
                      </div>
                    @else
                      <div class="text-center py-4">
                        <i class="fas fa-heart fa-2x text-muted mb-3"></i>
                        <p class="text-muted small">No favorites yet</p>
                      </div>
                    @endif
                  </div>
                </div>
              </div>
              
              <div class="col-md-6">
                <div class="dashboard-card h-100">
                  <div class="card-header bg-dark text-white">
                    <h5 class="mb-0"><i class="fas fa-star me-2"></i>Recent Reviews</h5>
                  </div>
                  <div class="card-body">
                    @if(isset($reviews) && $reviews->count() > 0)
                      @foreach($reviews as $review)
                      <div class="border-bottom pb-2 mb-2">
                        <h6 class="small mb-1">{{ $review->article->name }}</h6>
                        <div class="rating-stars mb-1">
                          @for($i = 1; $i <= 5; $i++)
                            <i class="fas fa-star{{ $i <= $review->rating ? '' : '-empty' }}"></i>
                          @endfor
                        </div>
                        @if($review->comment)
                          <p class="small text-muted mb-1">{{ Str::limit($review->comment, 60) }}</p>
                        @endif
                      </div>
                      @endforeach
                      <div class="text-center">
                        <a href="{{ route('dashboard') }}?section=reviews" class="btn btn-outline-dark btn-sm">View All</a>
                      </div>
                    @else
                      <div class="text-center py-4">
                        <i class="fas fa-star fa-2x text-muted mb-3"></i>
                        <p class="text-muted small">No reviews yet</p>
                      </div>
                    @endif
                  </div>
                </div>
              </div>
            </div>
            
            <!-- Review Suggestions -->
            @if(isset($reviewableArticles) && $reviewableArticles->count() > 0)
            <div class="dashboard-card">
              <div class="card-header bg-dark text-white">
                <h5 class="mb-0"><i class="fas fa-edit me-2"></i>Review Your Purchases</h5>
              </div>
              <div class="card-body">
                <p class="text-muted mb-3">Share your experience with products you've purchased:</p>
                <div class="row">
                  @foreach($reviewableArticles as $article)
                  <div class="col-md-4 mb-3">
                    <div class="text-center">
                      <img src="{{ $article->image ? asset('storage/' . $article->image) : asset('vendor/furni/images/product-1.png') }}" 
                           class="product-thumb mb-2" alt="{{ $article->name }}">
                      <h6 class="small mb-2">{{ Str::limit($article->name, 25) }}</h6>
                      <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#reviewModal{{ $article->id }}">
                        <i class="fas fa-star me-1"></i>Review
                      </button>
                    </div>
                    
                    <!-- Review Modal -->
                    <div class="modal fade" id="reviewModal{{ $article->id }}">
                      <div class="modal-dialog">
                        <div class="modal-content">
                          <div class="modal-header">
                            <h5 class="modal-title">Review {{ $article->name }}</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                          </div>
                          <form action="{{ route('dashboard.review.submit', $article->id) }}" method="POST">
                            @csrf
                            <div class="modal-body">
                              <div class="text-center mb-3">
                                <p class="mb-2">How would you rate this product?</p>
                                <div class="modal-rating">
                                  @for($i = 5; $i >= 1; $i--)
                                  <input type="radio" id="star{{ $i }}_{{ $article->id }}" name="rating" value="{{ $i }}" required>
                                  <label for="star{{ $i }}_{{ $article->id }}">★</label>
                                  @endfor
                                </div>
                              </div>
                              <div class="mb-3">
                                <label class="form-label">Order</label>
                                <select name="order_id" class="form-control" required>
                                  @foreach(Auth::user()->orders as $order)
                                    @if($order->items()->where('article_id', $article->id)->exists())
                                    <option value="{{ $order->id }}">Order #{{ $order->id }} ({{ $order->created_at->format('M d, Y') }})</option>
                                    @endif
                                  @endforeach
                                </select>
                              </div>
                              <div class="mb-3">
                                <label class="form-label">Your Review (Optional)</label>
                                <textarea name="comment" class="form-control" rows="3" placeholder="Share your experience..."></textarea>
                              </div>
                            </div>
                            <div class="modal-footer">
                              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                              <button type="submit" class="btn btn-primary">Submit Review</button>
                            </div>
                          </form>
                        </div>
                      </div>
                    </div>
                  </div>
                  @endforeach
                </div>
              </div>
            </div>
            @endif
            
          @elseif($section === 'orders')
          <!-- ALL ORDERS -->
            <div class="dashboard-card">
              <div class="card-header bg-dark text-white d-flex justify-content-between align-items-center">
                <h5 class="mb-0"><i class="fas fa-shopping-bag me-2"></i>My Orders</h5>
                <a href="{{ route('dashboard') }}" class="btn btn-sm btn-light">Back to Dashboard</a>
              </div>
              <div class="card-body">
                @if(isset($orders) && $orders->count() > 0)
                  @foreach($orders as $order)
                  <div class="border-bottom pb-3 mb-3">
                    <div class="row">
                      <div class="col-md-8">
                        <h6>Order #{{ $order->id }}</h6>
                        <p class="mb-1 small">Placed on {{ $order->created_at->format('F d, Y') }}</p>
                        <p class="mb-2">Total: <strong>${{ number_format($order->total_price, 2) }}</strong></p>
                        <span class="order-status-badge badge bg-{{ 
                          $order->status === 'pending' ? 'warning' : 
                          ($order->status === 'paid' ? 'info' : 
                          ($order->status === 'shipped' ? 'primary' : 
                          ($order->status === 'delivered' ? 'success' : 'secondary')))
                        }}">
                          {{ ucfirst($order->status) }}
                        </span>
                      </div>
                      <div class="col-md-4 text-end">
                        <a href="{{ route('order.confirmation', $order->id) }}" class="btn btn-sm btn-dark mb-2">View Details</a>
                        @php
                          $hoursSinceOrder = now()->diffInHours($order->created_at);
                          $canCancel = $order->status === 'pending' && $hoursSinceOrder <= 24 && $order->status !== 'cancelled';
                        @endphp
                        @if($canCancel)
                          <form action="{{ route('dashboard.order.cancel', $order->id) }}" method="POST" class="d-inline">
                            @csrf
                            <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Cancel order #{{ $order->id }}?')">
                              Cancel Order
                            </button>
                          </form>
                        @endif
                      </div>
                    </div>
                  </div>
                  @endforeach
                  
                  <!-- Pagination -->
                  <div class="mt-4">
                    {{ $orders->links() }}
                  </div>
                @else
                  <div class="text-center py-5">
                    <i class="fas fa-shopping-bag fa-3x text-muted mb-3"></i>
                    <h5>No orders yet</h5>
                    <p class="text-muted">Start shopping to see your orders here</p>
                    <a href="{{ route('shop') }}" class="btn btn-primary">Shop Now</a>
                  </div>
                @endif
              </div>
            </div>
            
          @elseif($section === 'favorites')
          <!-- ALL FAVORITES -->
            <div class="dashboard-card">
              <div class="card-header bg-dark text-white d-flex justify-content-between align-items-center">
                <h5 class="mb-0"><i class="fas fa-heart me-2"></i>My Favorites</h5>
                <a href="{{ route('dashboard') }}" class="btn btn-sm btn-light">Back to Dashboard</a>
              </div>
              <div class="card-body">
                @if(isset($favorites) && $favorites->count() > 0)
                  <div class="row">
                    @foreach($favorites as $favorite)
                    <div class="col-md-4 mb-4">
                      <div class="border rounded p-3 h-100">
                        <img src="{{ $favorite->article->image ? asset('storage/' . $favorite->article->image) : asset('vendor/furni/images/product-1.png') }}" 
                             class="img-fluid rounded mb-3" alt="{{ $favorite->article->name }}" style="height: 150px; width: 100%; object-fit: cover;">
                        <h6>{{ Str::limit($favorite->article->name, 30) }}</h6>
                        <p class="text-primary h5">${{ number_format($favorite->article->price, 2) }}</p>
                        <div class="d-flex justify-content-between">
                          <a href="{{ route('product.show', $favorite->article->slug) }}" class="btn btn-sm btn-dark">View</a>
                          <form action="{{ route('dashboard.favorite.toggle', $favorite->article_id) }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-sm btn-outline-danger">Remove</button>
                          </form>
                        </div>
                      </div>
                    </div>
                    @endforeach
                  </div>
                  
                  <!-- Pagination -->
                  <div class="mt-4">
                    {{ $favorites->links() }}
                  </div>
                @else
                  <div class="text-center py-5">
                    <i class="fas fa-heart fa-3x text-muted mb-3"></i>
                    <h5>No favorites yet</h5>
                    <p class="text-muted">Click the heart icon on products to add them here</p>
                    <a href="{{ route('shop') }}" class="btn btn-primary">Browse Products</a>
                  </div>
                @endif
              </div>
            </div>
            
          @elseif($section === 'reviews')
          <!-- ALL REVIEWS -->
            <div class="dashboard-card">
              <div class="card-header bg-dark text-white d-flex justify-content-between align-items-center">
                <h5 class="mb-0"><i class="fas fa-star me-2"></i>My Reviews</h5>
                <a href="{{ route('dashboard') }}" class="btn btn-sm btn-light">Back to Dashboard</a>
              </div>
              <div class="card-body">
                @if(isset($reviews) && $reviews->count() > 0)
                  @foreach($reviews as $review)
                  <div class="border-bottom pb-3 mb-3">
                    <div class="row">
                      <div class="col-md-3">
                        <img src="{{ $review->article->image ? asset('storage/' . $review->article->image) : asset('vendor/furni/images/product-1.png') }}" 
                             class="img-fluid rounded" alt="{{ $review->article->name }}" style="height: 100px; object-fit: cover;">
                      </div>
                      <div class="col-md-9">
                        <h6>{{ $review->article->name }}</h6>
                        <div class="rating-stars mb-2">
                          @for($i = 1; $i <= 5; $i++)
                            <i class="fas fa-star{{ $i <= $review->rating ? '' : '-empty' }}"></i>
                          @endfor
                          <span class="ms-2">{{ $review->rating }}/5</span>
                        </div>
                        @if($review->comment)
                          <p class="mb-2">{{ $review->comment }}</p>
                        @endif
                        <p class="text-muted small mb-0">
                          Order #{{ $review->order_id }} • {{ $review->created_at->format('F d, Y') }}
                        </p>
                      </div>
                    </div>
                  </div>
                  @endforeach
                  
                  <!-- Pagination -->
                  <div class="mt-4">
                    {{ $reviews->links() }}
                  </div>
                @else
                  <div class="text-center py-5">
                    <i class="fas fa-star fa-3x text-muted mb-3"></i>
                    <h5>No reviews yet</h5>
                    <p class="text-muted">Review products you've purchased to see them here</p>
                  </div>
                @endif
              </div>
            </div>
            
          @endif
          
        </div>
      </div>
    </div>
  </div>

  <!-- Footer -->
  <footer class="footer-section">
    <div class="container">
      <div class="border-top copyright pt-4">
        <div class="row">
          <div class="col-lg-12 text-center">
            <p class="mb-0">© {{ date('Y') }} Boutique. All Rights Reserved.</p>
          </div>
        </div>
      </div>
    </div>
  </footer>

  <!-- Scripts -->
  <script src="{{ asset('vendor/furni/js/bootstrap.bundle.min.js') }}"></script>
  <script>
  // Success/Error Messages
  @if(session('success'))
    alert('{{ session('success') }}');
  @endif
  
  @if(session('error'))
    alert('{{ session('error') }}');
  @endif
  
  // Favorite toggle with AJAX
  document.addEventListener('click', function(e) {
    if (e.target.closest('.favorite-toggle')) {
      e.preventDefault();
      const form = e.target.closest('form');
      const articleId = form.dataset.articleId;
      
      fetch(form.action, {
        method: 'POST',
        headers: {
          'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
          'Content-Type': 'application/json',
        },
        body: JSON.stringify({ article_id: articleId })
      })
      .then(response => response.json())
      .then(data => {
        if (data.success) {
          const heartIcon = form.querySelector('i');
          if (data.action === 'added') {
            heartIcon.className = 'fas fa-heart text-danger';
          } else {
            heartIcon.className = 'far fa-heart text-muted';
          }
          alert(data.message);
        }
      });
    }
  });
  </script>
</body>
</html>