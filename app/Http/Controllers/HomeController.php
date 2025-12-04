<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Article;

class HomeController extends Controller
{
    public function index()
    {
        $featured = Article::where('is_featured', true)->take(8)->get();
        $latest = Article::orderByDesc('created_at')->take(6)->get();
        return view('home', compact('featured','latest'));
    }
}
