<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Article;
use App\Models\Brand;
use App\Models\Category;

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
 public function show($slug)
    {
        $article = Article::where('slug', $slug)
            ->with(['category', 'brand'])
            ->firstOrFail();
            
        // Increment view count
        $article->increment('views_count');
        
        return view('articles.show', compact('article'));
    }}