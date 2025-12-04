<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Article;
use App\Models\Category;
use App\Models\Brand;
use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class ArticleSeeder extends Seeder
{
    public function run()
    {
        // Ensure public->storage link exists (seed will still copy files)
        if (!Storage::disk('public')->exists('/')) {
            // do nothing; instruct user to run php artisan storage:link before running seeders
        }

        $products = [
            [
                'name' => 'Nordic Chair',
                'slug' => 'nordic-chair',
                'excerpt' => 'Chaise nordique élégante en bois et tissu.',
                'description' => 'Chaise au style nordique, structure en bois massif avec assise confortable. Idéale pour salon et bureau.',
                'price' => 50.00,
                'stock' => 25,
                'template_image' => 'product-1.png',
                'category' => 'meubles',
                'brand' => 'nordichome',
                'is_featured' => true,
                'sales_count' => 120,
            ],
            [
                'name' => 'Kruzo Aero Chair',
                'slug' => 'kruzo-aero-chair',
                'excerpt' => 'Fauteuil ergonomique Kruzo Aero.',
                'description' => 'Fauteuil de bureau ergonomique Kruzo Aero, parfait pour un usage prolongé.',
                'price' => 78.00,
                'stock' => 15,
                'template_image' => 'product-2.png',
                'category' => 'meubles',
                'brand' => 'furnico',
                'is_featured' => true,
                'sales_count' => 90,
            ],
            [
                'name' => 'Ergonomic Chair',
                'slug' => 'ergonomic-chair',
                'excerpt' => 'Chaise ergonomique moderne.',
                'description' => 'Chaise ergonomique avec support lombaire et revêtement respirant.',
                'price' => 43.00,
                'stock' => 40,
                'template_image' => 'product-3.png',
                'category' => 'meubles',
                'brand' => 'maisonplus',
                'is_featured' => false,
                'sales_count' => 60,
            ],
            [
                'name' => 'Modern Sofa',
                'slug' => 'modern-sofa',
                'excerpt' => 'Canapé moderne confortable.',
                'description' => 'Canapé 3 places, revêtement tissu, design moderne.',
                'price' => 420.00,
                'stock' => 8,
                'template_image' => 'product-1.png', // reuse if template limited
                'category' => 'decoration',
                'brand' => 'decoart',
                'is_featured' => true,
                'sales_count' => 30,
            ],
            [
                'name' => 'Table Lamp',
                'slug' => 'table-lamp',
                'excerpt' => 'Lampe de table sculpturale.',
                'description' => 'Lampe décorative idéale pour chevet ou bureau.',
                'price' => 35.00,
                'stock' => 60,
                'template_image' => 'product-2.png',
                'category' => 'luminaires',
                'brand' => 'decoart',
                'is_featured' => false,
                'sales_count' => 45,
            ],
            // add more products as desired
        ];

        // Map categories and brands to ids
        $categoryMap = Category::pluck('id','slug')->toArray();
        $brandMap = Brand::pluck('id','slug')->toArray();
        $userMap = User::pluck('id','email')->toArray();

        // Choose an author (admin if exists)
        $authorId = User::where('role','admin')->value('id') ?? User::first()->id ?? null;

        // Destination folder in storage/public
        $destFolder = storage_path('app/public/products');
        if (!File::exists($destFolder)) {
            File::makeDirectory($destFolder, 0755, true);
        }

        foreach ($products as $p) {
            // copy image from public/vendor/furni/images/<filename> to storage/app/public/products/<filename>
            $src = public_path('vendor/furni/images/'.$p['template_image']);
            $dst = $destFolder . '/' . $p['template_image'];
            if (File::exists($src)) {
                // copy only if not exists to save time
                if (!File::exists($dst)) {
                    File::copy($src, $dst);
                }
                $imagePath = 'products/'.$p['template_image'];
            } else {
                // fallback: no image found, set null
                $imagePath = null;
            }

            $categorySlug = $p['category'] ?? null;
            $brandSlug = $p['brand'] ?? null;

            $categoryId = $categoryMap[$categorySlug] ?? Category::where('slug',$categorySlug)->value('id') ?? null;
            $brandId = $brandMap[$brandSlug] ?? Brand::where('slug',$brandSlug)->value('id') ?? null;

            Article::updateOrCreate(
                ['slug' => $p['slug']],
                [
                    'name' => $p['name'],
                    'description' => $p['description'],
                    'excerpt' => $p['excerpt'],
                    'price' => $p['price'],
                    'stock' => $p['stock'],
                    'image' => $imagePath, // stored under storage/app/public/products/...
                    'views_count' => rand(10, 500),
                    'is_featured' => $p['is_featured'] ?? false,
                    'sales_count' => $p['sales_count'] ?? 0,
                    'category_id' => $categoryId,
                    'brand_id' => $brandId,
                    'author_id' => $authorId,
                    'published_at' => Carbon::now()->subDays(rand(0, 60)),
                ]
            );
        }
    }
}
