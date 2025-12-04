<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Article extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'excerpt',
        'price',
        'stock',
        'image',
        'views_count',
        'is_featured',
        'sales_count',
        'category_id',
        'brand_id',
        'author_id',
        'published_at',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'views_count' => 'integer',
        'is_featured' => 'boolean',
        'sales_count' => 'integer',
        'published_at' => 'datetime',
        'stock' => 'integer',
    ];

    protected static function booted()
    {
        static::creating(function ($article) {
            if (empty($article->slug) && !empty($article->name)) {
                $article->slug = Str::slug($article->name) . '-' . substr(uniqid(), -6);
            }
        });
    }

    // Relations
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function brand(): BelongsTo
    {
        return $this->belongsTo(Brand::class);
    }

    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class);
    }

    public function favorites(): HasMany
    {
        return $this->hasMany(Favorite::class);
    }

    // Helpers
    public function averageRating(): float
    {
        return round($this->reviews()->avg('rating') ?? 0, 2);
    }

    public function isFavoritedBy(?User $user): bool
    {
        if (! $user) {
            return false;
        }
        return $this->favorites()->where('user_id', $user->id)->exists();
    }

    public function inStock(): bool
    {
        return $this->stock > 0;
    }
}
