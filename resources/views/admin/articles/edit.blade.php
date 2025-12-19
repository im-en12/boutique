@extends('admin.layout')

@section('content')
<div class="container-fluid">
    <!-- Header -->
    <div class="admin-header d-flex justify-content-between align-items-center">
        <div>
            <h1 class="h3 mb-0">Edit Product</h1>
            <p class="text-muted mb-0">Update product information</p>
        </div>
        <div>
            <a href="{{ route('admin.articles.index') }}" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left me-2"></i> Back to Products
            </a>
        </div>
    </div>
    
    <!-- Product Form -->
    <div class="table-card">
        <div class="card-body">
            <form action="{{ route('admin.articles.update', $article) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                
                <div class="row">
                    <!-- Basic Information -->
                    <div class="col-lg-8">
                        <div class="mb-4">
                            <h5 class="mb-3"><i class="fas fa-info-circle me-2"></i>Basic Information</h5>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Product Name *</label>
                                    <input type="text" name="name" class="form-control" 
                                           value="{{ old('name', $article->name) }}" required>
                                </div>
                                
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Price ($) *</label>
                                    <input type="number" step="0.01" name="price" class="form-control" 
                                           value="{{ old('price', $article->price) }}" required>
                                </div>
                                
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Stock Quantity *</label>
                                    <input type="number" name="stock" class="form-control" 
                                           value="{{ old('stock', $article->stock) }}" required>
                                </div>
                                
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Category</label>
                                    <select name="category_id" class="form-control">
                                        <option value="">Select Category</option>
                                        @foreach($categories as $category)
                                            <option value="{{ $category->id }}" 
                                                    {{ (old('category_id', $article->category_id) == $category->id) ? 'selected' : '' }}>
                                                {{ $category->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Brand</label>
                                    <select name="brand_id" class="form-control">
                                        <option value="">Select Brand</option>
                                        @foreach($brands as $brand)
                                            <option value="{{ $brand->id }}" 
                                                    {{ (old('brand_id', $article->brand_id) == $brand->id) ? 'selected' : '' }}>
                                                {{ $brand->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                
                                <div class="col-md-6 mb-3">
                                    <label class="form-label d-block">Product Status</label>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="checkbox" name="is_featured" 
                                               id="is_featured" value="1" 
                                               {{ (old('is_featured', $article->is_featured) ? 'checked' : '') }}>
                                        <label class="form-check-label" for="is_featured">
                                            Featured Product
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Description -->
                        <div class="mb-4">
                            <h5 class="mb-3"><i class="fas fa-align-left me-2"></i>Description</h5>
                            <div class="mb-3">
                                <label class="form-label">Short Excerpt</label>
                                <textarea name="excerpt" class="form-control" rows="2">{{ old('excerpt', $article->excerpt) }}</textarea>
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label">Full Description</label>
                                <textarea name="description" class="form-control" rows="6">{{ old('description', $article->description) }}</textarea>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Image Upload -->
                    <div class="col-lg-4">
                        <div class="mb-4">
                            <h5 class="mb-3"><i class="fas fa-image me-2"></i>Product Image</h5>
                            <div class="card p-3 text-center">
                                <!-- Current Image -->
                                @if($article->image)
                                    <div id="currentImage" class="mb-3">
                                        <img src="{{ asset('storage/' . $article->image) }}" 
                                             class="img-fluid rounded mb-2" 
                                             style="max-height: 200px; object-fit: cover;">
                                        <p class="small text-muted">Current Image</p>
                                    </div>
                                @endif
                                
                                <!-- New Image Upload -->
                                <div id="imagePreview" class="{{ $article->image ? 'd-none' : '' }}">
                                    <i class="fas fa-cloud-upload-alt fa-2x text-muted mb-2"></i>
                                    <p class="text-muted small">Upload new image</p>
                                </div>
                                
                                <input type="file" name="image" id="imageInput" class="form-control mt-2" 
                                       accept="image/*" onchange="previewImage(event)">
                                <small class="text-muted d-block mt-2">
                                    Leave empty to keep current image
                                </small>
                            </div>
                        </div>
                        
                        <!-- Product Stats & Actions -->
                        <div class="card">
                            <div class="card-body">
                                <h6 class="mb-3"><i class="fas fa-chart-bar me-2"></i>Product Stats</h6>
                                <ul class="list-unstyled">
                                    <li class="mb-2">
                                        <i class="fas fa-eye text-info me-2"></i>
                                        <span class="text-muted">Views:</span>
                                        <strong class="float-end">{{ $article->views_count }}</strong>
                                    </li>
                                    <li class="mb-2">
                                        <i class="fas fa-shopping-cart text-success me-2"></i>
                                        <span class="text-muted">Sales:</span>
                                        <strong class="float-end">{{ $article->sales_count }}</strong>
                                    </li>
                                    <li class="mb-2">
                                        <i class="fas fa-star text-warning me-2"></i>
                                        <span class="text-muted">Rating:</span>
                                        <strong class="float-end">{{ $article->averageRating() }}/5</strong>
                                    </li>
                                </ul>
                                
                                <hr>
                                
                                <!-- Update Actions -->
                                <div class="d-grid gap-2">
                                    <button type="submit" class="btn btn-admin-primary">
                                        <i class="fas fa-save me-2"></i>Update Product
                                    </button>
                                    
                                    <a href="{{ route('admin.articles.show', $article->slug) }}" 
                                       target="_blank" class="btn btn-outline-info">
                                        <i class="fas fa-external-link-alt me-2"></i>View Live
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function previewImage(event) {
    const input = event.target;
    const preview = document.getElementById('imagePreview');
    const currentImage = document.getElementById('currentImage');
    
    // Hide current image when uploading new one
    if (currentImage) {
        currentImage.style.display = 'none';
    }
    
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        
        reader.onload = function(e) {
            preview.innerHTML = `
                <img src="${e.target.result}" 
                     class="img-fluid rounded" 
                     style="max-height: 200px; object-fit: cover;">
                <p class="small text-muted mt-2">New Image Preview</p>
            `;
            preview.classList.remove('d-none');
        }
        
        reader.readAsDataURL(input.files[0]);
    }
}
</script>
@endsection