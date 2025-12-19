<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel - {{ config('app.name') }}</title>
    
    <link href="{{ asset('vendor/furni/css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    
    <style>
        /* === Green Woody Admin Theme === */
        body {
            background: #e9f1ea; /* light greenish background */
            font-family: 'Arial', sans-serif;
        }

        /* Sidebar */
        .sidebar {
            background: #3b5d50; /* dark green */
            color: #f0f0f0;
            min-height: 100vh;
            width: 250px;
            position: fixed;
            padding-top: 20px;
        }

        .sidebar h4 {
            color: #f0f0f0;
            font-weight: 700;
            text-align: center;
        }

        .sidebar small {
            display: block;
            text-align: center;
            margin-bottom: 15px;
            color: #d0d0d0;
        }

        .sidebar a {
            color: #cbd5c3;
            padding: 10px 20px;
            display: block;
            text-decoration: none;
            transition: all 0.3s ease;
        }

        .sidebar a:hover, .sidebar a.active {
            color: #ffffff;
            background: #5a7a6e;
            border-radius: 6px;
        }

        .sidebar form button {
            background-color: #5a7a6e;
            color: #fff;
            border-radius: 6px;
            transition: all 0.3s ease;
        }

        .sidebar form button:hover {
            background-color: #476359;
        }

        /* Admin content */
        .admin-content {
            margin-left: 250px;
            padding: 25px;
        }

        /* Alert messages */
        .alert {
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        }

        /* Order status badges */
        .badge-status {
            padding: 5px 12px;
            border-radius: 20px;
            font-size: 0.85rem;
            font-weight: 500;
            text-transform: uppercase;
        }

        .badge-pending { background: #ffc107; color: #000; }
        .badge-paid { background: #17a2b8; color: #fff; }
        .badge-shipped { background: #007bff; color: #fff; }
        .badge-delivered { background: #28a745; color: #fff; }
        .badge-cancelled { background: #dc3545; color: #fff; }

        /* Tables */
        table {
            background: #fff;
            border-radius: 8px;
            overflow: hidden;
        }

        table th {
            background-color: #3b5d50;
            color: #fff;
        }

        table td, table th {
            padding: 12px 15px;
        }

        table tr:nth-child(even) {
            background-color: #f2f7f4;
        }

        table tr:hover {
            background-color: #e0ede6;
        }
    </style>
</head>
<body>
    <div class="sidebar">
        <h4>Admin Panel</h4>
        <small>Welcome, {{ Auth::user()->name }}</small>
        <nav class="nav flex-column">
            <a href="{{ route('admin.dashboard') }}"><i class="fas fa-tachometer-alt me-2"></i> Dashboard</a>
            <a href="{{ route('admin.orders.index') }}"><i class="fas fa-shopping-bag me-2"></i> Orders</a>
            <a href="{{ route('admin.articles.index') }}"><i class="fas fa-box me-2"></i> Products</a>
            <hr style="border-color:#5a7a6e;">
            <a href="{{ route('dashboard') }}"><i class="fas fa-user me-2"></i> My Account</a>
            <a href="{{ url('/') }}"><i class="fas fa-store me-2"></i> View Store</a>
            <hr style="border-color:#5a7a6e;">
            <form method="POST" action="{{ route('logout') }}" class="p-2">
                @csrf
                <button type="submit" class="btn btn-sm w-100">
                    <i class="fas fa-sign-out-alt me-2"></i> Logout
                </button>
            </form>
        </nav>
    </div>
    
    <div class="admin-content">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif
        
        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif
        
        @yield('content')
    </div>
    
    <script src="{{ asset('vendor/furni/js/bootstrap.bundle.min.js') }}"></script>
</body>
</html>
