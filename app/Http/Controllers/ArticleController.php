<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Article;
use App\Models\Brand;
use App\Models\Category;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ArticleController extends Controller
{
    public function shop(Request $request)
    {
        // Start with all articles
        $query = Article::query();
        
        // Search filter
        if ($request->has('search') && $request->search != '') {
            $searchTerm = $request->search;
            $query->where(function($q) use ($searchTerm) {
                $q->where('name', 'like', '%' . $searchTerm . '%')
                  ->orWhere('description', 'like', '%' . $searchTerm . '%')
                  ->orWhere('excerpt', 'like', '%' . $searchTerm . '%');
            });
        }
        
        // Category filter
        if ($request->has('category') && $request->category != '') {
            $query->whereHas('category', function($q) use ($request) {
                $q->where('slug', $request->category);
            });
        }
        
        // Price range filter
        if ($request->has('min_price') && $request->min_price != '') {
            $query->where('price', '>=', $request->min_price);
        }
        
        if ($request->has('max_price') && $request->max_price != '') {
            $query->where('price', '<=', $request->max_price);
        }
        
        // Brand filter
        if ($request->has('brand') && $request->brand != '') {
            $query->whereHas('brand', function($q) use ($request) {
                $q->where('slug', $request->brand);
            });
        }
        
        // Sorting
        $sort = $request->get('sort', 'newest');
        switch ($sort) {
            case 'price_low':
                $query->orderBy('price', 'asc');
                break;
            case 'price_high':
                $query->orderBy('price', 'desc');
                break;
            case 'popular':
                $query->orderBy('sales_count', 'desc');
                break;
            case 'featured':
                $query->where('is_featured', true)->orderBy('created_at', 'desc');
                break;
            default:
                $query->orderBy('created_at', 'desc');
                break;
        }
        
        // Get articles with pagination
        $articles = $query->with(['category', 'brand'])->paginate(12);
        
        // Get all categories and brands for filters
        $categories = Category::all();
        $brands = Brand::all();
        
        // Get min and max prices for range slider
        $minPrice = Article::min('price');
        $maxPrice = Article::max('price');
        
        return view('shop', compact(
            'articles', 
            'categories', 
            'brands', 
            'minPrice', 
            'maxPrice'
        ));

    }
    //////:
public function show($slug)
{
    $article = Article::where('slug', $slug)
        ->with(['category', 'brand'])
        ->firstOrFail();
        
    // Increment view count
    $article->increment('views_count');
    
    // Get related products (products from same category)
    $relatedProducts = Article::where('category_id', $article->category_id)
        ->where('id', '!=', $article->id)
        ->take(4)
        ->get();
    
    return view('single-article', compact('article', 'relatedProducts'));
}
public function adminIndex(Request $request)
{
    
    
  if(!Auth::check() || !Auth::user()->isAdmin()) {
     abort(403, 'Unauthorized');
  }

    $articles = Article::with(['category', 'brand'])
        ->latest()
        ->paginate(20);
    $categories = Category::all();
    $brands = Brand::all();
        
    return view('admin.articles.index', compact('articles', 'categories', 'brands'));
  
    
}
public function create(){
    if (!Auth::check() || !Auth::user()->isAdmin()) {
        abort(403, 'Unauthorized');
    }
    $categories = Category::all();
    $brands = Brand::all();
    return view('admin.articles.create', compact('categories', 'brands'));
}
  /**
     * ADMIN: Store new article
     */
    public function store(Request $request)
    {
        if (!Auth::check() || !Auth::user()->isAdmin()) {
            abort(403, 'Unauthorized access.');
        }
        
        $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'description' => 'nullable|string',
            'excerpt' => 'nullable|string|max:255',
            'category_id' => 'nullable|exists:categories,id',
            'brand_id' => 'nullable|exists:brands,id',
            'image' => 'nullable|image|max:2048',
            'is_featured' => 'nullable|boolean',
        ]);
        
        $data = $request->except('image');
        
        // Handle image upload
        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('articles', 'public');
            $data['image'] = $path;
        }
        
        // Set author if not provided
        if (!isset($data['author_id'])) {
            $data['author_id'] = Auth::id();
        }
        
        // Generate slug from name
        $data['slug'] = \Illuminate\Support\Str::slug($data['name']) . '-' . substr(uniqid(), -6);
        
        Article::create($data);
        
        return redirect()->route('admin.articles.index')
            ->with('success', 'Article created successfully!');
    }
    
    /**
     * ADMIN: Show edit article form
     */
    public function edit(Article $article)
    {
        if (!Auth::check() || !Auth::user()->isAdmin()) {
            abort(403, 'Unauthorized access.');
        }
        
        $categories = Category::all();
        $brands = Brand::all();
        
        return view('admin.articles.edit', compact('article', 'categories', 'brands'));
    }
    
    /**
     * ADMIN: Update article
     */
    public function update(Request $request, Article $article)
    {
        if (!Auth::check() || !Auth::user()->isAdmin()) {
            abort(403, 'Unauthorized access.');
        }
        
        $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'description' => 'nullable|string',
            'excerpt' => 'nullable|string|max:255',
            'category_id' => 'nullable|exists:categories,id',
            'brand_id' => 'nullable|exists:brands,id',
            'image' => 'nullable|image|max:2048',
            'is_featured' => 'nullable|boolean',
        ]);
        
        $data = $request->except('image');
        
        // Handle image upload
        if ($request->hasFile('image')) {
            // Delete old image if exists
            if ($article->image) {
                Storage::disk('public')->delete($article->image);
            }
            
            $path = $request->file('image')->store('articles', 'public');
            $data['image'] = $path;
        }
        
        // Update slug if name changed
        if ($article->name !== $data['name']) {
            $data['slug'] = \Illuminate\Support\Str::slug($data['name']) . '-' . substr(uniqid(), -6);
        }
        
        $article->update($data);
        
        return redirect()->route('admin.articles.index')
            ->with('success', 'Article updated successfully!');
    }
    
    /**
     * ADMIN: Delete article
     */
    public function destroy(Article $article)
    {
        if (!Auth::check() || !Auth::user()->isAdmin()) {
            abort(403, 'Unauthorized access.');
        }
        
        // Delete image if exists
        if ($article->image) {
            Storage::disk('public')->delete($article->image);
        }
        
        $article->delete();
        
        return redirect()->route('admin.articles.index')
            ->with('success', 'Article deleted successfully!');
    }
}
