<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Brand;
use Illuminate\Support\Str;

class BrandSeeder extends Seeder
{
    public function run()
    {
        $brands = [
            ['name' => 'FurniCo', 'slug' => 'furnico'],
            ['name' => 'MaisonPlus', 'slug' => 'maisonplus'],
            ['name' => 'DecoArt', 'slug' => 'decoart'],
            ['name' => 'NordicHome', 'slug' => 'nordichome'],
        ];

        foreach ($brands as $b) {
            Brand::updateOrCreate(
                ['slug' => $b['slug']],
                ['name' => $b['name'], 'description' => $b['name']]
            );
        }
    }
}
