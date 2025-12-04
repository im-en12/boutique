<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Article;

class ReviewController extends Controller
{
    public function store(Request $request, Article $article)
    {
        $user = $request->user();
        if (! $user) {
            return redirect()->route('login');
        }

        $data = $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:2000',
        ]);

        $article->reviews()->create([
            'user_id' => $user->id,
            'rating' => $data['rating'],
            'comment' => $data['comment'] ?? null,
        ]);

        return back()->with('success', 'Merci pour votre avis !');
    }
}
