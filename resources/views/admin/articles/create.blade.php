@extends('admin.layout')

@section('content')
<div class="container-fluid">
    <!-- Header -->
    <div class="admin-header d-flex justify-content-between align-items-center">
        <div>
            <h1 class="h3 mb-0">Add New Product</h1>
            <p class="text-muted mb-0">Fill in the details to add a new product to your store</p>
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
            <form action="{{ route('admin.articles.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                
                <div class="row">
                    <!-- Basic Information -->
                    <div class="col-lg-8">
                        <div class="mb-4">
                            <h5 class="mb-3"><i class="fas fa-info-circle me-2"></i>Basic Information</h5>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Product Name *</label>
                                    <input type="text" name="name" class="form-control" 
                                           value="{{ old('name') }}" required 
                                           placeholder="Enter product name">
                                    @error('name')
                                        <div class="text-danger small">{{ $message }}</div>
                                    @enderror
                                </div>
                                
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Price ($) *</label>
                                    <input type="number" step="0.01" name="price" class="form-control" 
                                           value="{{ old('price') }}" required 
                                           placeholder="0.00">
                                    @error('price')
                                        <div class="text-danger small">{{ $message }}</div>
                                    @enderror
                                </div>
                                
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Stock Quantity *</label>
                                    <input type="number" name="stock" class="form-control" 
                                           value="{{ old('stock', 0) }}" required>
                                    @error('stock')
                                        <div class="text-danger small">{{ $message }}</div>
                                    @enderror
                                </div>
                                
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Category</label>
                                    <select name="category_id" class="form-control">
                                        <option value="">Select Category</option>
                                        @foreach($categories as $category)
                                            <option value="{{ $category->id }}" 
                                                    {{ old('category_id') == $category->id ? 'selected' : '' }}>
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
                                                    {{ old('brand_id') == $brand->id ? 'selected' : '' }}>
                                                {{ $brand->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                
                                <div class="col-md-6 mb-3">
                                    <label class="form-label d-block">Product Status</label>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="checkbox" name="is_featured" 
                                               id="is_featured" value="1" {{ old('is_featured') ? 'checked' : '' }}>
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
                                <textarea name="excerpt" class="form-control" rows="2" 
                                          placeholder="Brief product description (appears in product listings)">{{ old('excerpt') }}</textarea>
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label">Full Description</label>
                                <textarea name="description" class="form-control" rows="6" 
                                          placeholder="Detailed product description">{{ old('description') }}</textarea>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Image Upload -->
                    <div class="col-lg-4">
                        <div class="mb-4">
                            <h5 class="mb-3"><i class="fas fa-image me-2"></i>Product Image</h5>
                            <div class="card border-dashed p-4 text-center">
                                <div id="imagePreview" class="mb-3">
                                    <i class="fas fa-cloud-upload-alt fa-3x text-muted mb-3"></i>
                                    <p class="text-muted">Click to upload product image</p>
                                </div>
                                <input type="file" name="image" id="imageInput" class="form-control" 
                                       accept="image/*" onchange="previewImage(event)">
                                <small class="text-muted d-block mt-2">
                                    Recommended: 800x800px, max 2MB. JPG, PNG, or GIF.
                                </small>
                                @error('image')
                                    <div class="text-danger small mt-2">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <!-- Publish Options -->
                        <div class="card">
                            <div class="card-body">
                                <h6 class="mb-3"><i class="fas fa-paper-plane me-2"></i>Publish</h6>
                                <div class="d-grid gap-2">
                                    <button type="submit" class="btn btn-admin-primary">
                                        <i class="fas fa-save me-2"></i>Create Product
                                    </button>
                                    <a href="{{ route('admin.articles.index') }}" class="btn btn-outline-secondary">
                                        Cancel
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
    
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        
        reader.onload = function(e) {
            preview.innerHTML = `
                <img src="${e.target.result}" 
                     class="img-fluid rounded" 
                     style="max-height: 200px; object-fit: cover;">
                <p class="mt-2 small text-muted">${input.files[0].name}</p>
            `;
        }
        
        reader.readAsDataURL(input.files[0]);
    }
}
</script>

<style>
.border-dashed {
    border: 2px dashed #dee2e6;
    border-radius: 10px;
}
</style>
@endsection