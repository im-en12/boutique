@extends('admin.layout')

@section('content')
<div class="container-fluid">
    <!-- Header -->
    <div class="admin-header d-flex justify-content-between align-items-center">
        <div>
            <h1 class="h3 mb-0">Manage Products</h1>
            <p class="text-muted mb-0">Add, edit, or remove products from your store</p>
        </div>
        <div>
            <a href="{{ route('admin.articles.create') }}" class="btn btn-admin-primary">
                <i class="fas fa-plus me-2"></i>Add New Product
            </a>
        </div>
    </div>
    
    <!-- Products Table -->
    <div class="table-card">
        <div class="card-body">
            @if($articles->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Image</th>
                                <th>Product Name</th>
                                <th>Price</th>
                                <th>Stock</th>
                                <th>Category</th>
                                <th>Views</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($articles as $article)
                            <tr>
                                <td>{{ $article->id }}</td>
                                <td>
                                    @if($article->image)
                                        <img src="{{ asset('storage/' . $article->image) }}" 
                                             class="product-img-sm" 
                                             alt="{{ $article->name }}">
                                    @else
                                        <div class="product-img-sm bg-light d-flex align-items-center justify-content-center">
                                            <i class="fas fa-image text-muted"></i>
                                        </div>
                                    @endif
                                </td>
                                <td>
                                    <div>
                                        <strong>{{ $article->name }}</strong>
                                        @if($article->is_featured)
                                            <span class="badge bg-info ms-2">Featured</span>
                                        @endif
                                    </div>
                                    <small class="text-muted">{{ Str::limit($article->excerpt ?? $article->description, 50) }}</small>
                                </td>
                                <td>
                                    <strong class="text-primary">${{ number_format($article->price, 2) }}</strong>
                                </td>
                                <td>
                                    @if($article->stock > 10)
                                        <span class="badge bg-success">{{ $article->stock }} in stock</span>
                                    @elseif($article->stock > 0)
                                        <span class="badge bg-warning">{{ $article->stock }} low stock</span>
                                    @else
                                        <span class="badge bg-danger">Out of stock</span>
                                    @endif
                                </td>
                                <td>
                                    {{ $article->category->name ?? '-' }}
                                </td>
                                <td>
                                    <span class="badge bg-secondary">{{ $article->views_count }} views</span>
                                </td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <a href="{{ route('admin.articles.show', $article->slug) }}" 
                                           target="_blank" class="btn btn-sm btn-outline-info" title="View">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('admin.articles.edit', $article) }}" 
                                           class="btn btn-sm btn-outline-primary" title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="{{ route('admin.articles.destroy', $article) }}" 
                                              method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger" 
                                                    title="Delete" onclick="return confirm('Delete {{ $article->name }}?')">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                
                <!-- Pagination -->
                <div class="d-flex justify-content-between align-items-center mt-3">
                    <div class="text-muted">
                        Showing {{ $articles->firstItem() }} to {{ $articles->lastItem() }} of {{ $articles->total() }} products
                    </div>
                    <div>
                        {{ $articles->links() }}
                    </div>
                </div>
            @else
                <div class="text-center py-5">
                    <i class="fas fa-box fa-3x text-muted mb-3"></i>
                    <h5>No products found</h5>
                    <p class="text-muted">Get started by adding your first product</p>
                    <a href="{{ route('admin.articles.create') }}" class="btn btn-admin-primary">
                        <i class="fas fa-plus me-2"></i>Add Your First Product
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection