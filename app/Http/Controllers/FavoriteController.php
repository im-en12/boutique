<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Article;

class FavoriteController extends Controller
{
    public function toggle(Request $request, Article $article)
    {
        $user = $request->user();
        if (! $user) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        $exists = $user->favorites()->where('article_id', $article->id)->exists();

        if ($exists) {
            $user->favorites()->where('article_id', $article->id)->delete();
            return response()->json(['status' => 'removed']);
        } else {
            $user->favorites()->create(['article_id' => $article->id]);
            return response()->json(['status' => 'added']);
        }
    }
}
