<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    public function run()
    {
        $categories = [
            ['name' => 'Décoration', 'slug' => 'decoration'],
            ['name' => 'Meubles', 'slug' => 'meubles'],
            ['name' => 'Luminaires', 'slug' => 'luminaires'],
            ['name' => 'Textile', 'slug' => 'textile'],
            ['name' => 'Rangement', 'slug' => 'rangement'],
        ];

        foreach ($categories as $c) {
            Category::updateOrCreate(
                ['slug' => $c['slug']],
                ['name' => $c['name'], 'description' => $c['name']]
            );
        }
    }
}
