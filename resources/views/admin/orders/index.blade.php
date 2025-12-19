@extends('admin.layout')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Manage Orders</h1>
    </div>
    
    <!-- Status Filter -->
    <div class="card mb-4">
        <div class="card-body">
            <div class="btn-group">
                <a href="{{ route('admin.orders.index') }}" class="btn btn-outline-dark {{ !request('status') ? 'active' : '' }}">
                    All Orders
                </a>
                <a href="{{ route('admin.orders.index', ['status' => 'pending']) }}" class="btn btn-outline-warning {{ request('status') == 'pending' ? 'active' : '' }}">
                    Pending
                </a>
                <a href="{{ route('admin.orders.index', ['status' => 'delivered']) }}" class="btn btn-outline-success {{ request('status') == 'delivered' ? 'active' : '' }}">
                    Delivered
                </a>
                <a href="{{ route('admin.orders.index', ['status' => 'cancelled']) }}" class="btn btn-outline-danger {{ request('status') == 'cancelled' ? 'active' : '' }}">
                    Cancelled
                </a>
            </div>
        </div>
    </div>
    
    <!-- Orders Table -->
    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Order ID</th>
                            <th>Customer</th>
                            <th>Total</th>
                            <th>Status</th>
                            <th>Date</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($orders as $order)
                        <tr>
                            <td><strong>#{{ $order->id }}</strong></td>
                            <td>{{ $order->user->name }}</td>
                            <td>${{ number_format($order->total_price, 2) }}</td>
                            <td>
                                <span class="badge bg-{{ 
                                    $order->status === 'pending' ? 'warning' : 
                                    ($order->status === 'paid' ? 'info' : 
                                    ($order->status === 'shipped' ? 'primary' : 
                                    ($order->status === 'delivered' ? 'success' : 'secondary')))
                                }}">
                                    {{ ucfirst($order->status) }}
                                </span>
                            </td>
                            <td>{{ $order->created_at->format('M d, Y') }}</td>
                            <td>
                                <a href="{{ route('admin.orders.show', $order) }}" class="btn btn-sm btn-primary">
                                    <i class="fas fa-eye"></i>
                                </a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            
            {{ $orders->links() }}
        </div>
    </div>
</div>
@endsection