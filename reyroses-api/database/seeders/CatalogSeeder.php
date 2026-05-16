<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Support\Str;

class CatalogSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Creamos la categoría principal
        $category = Category::create([
            'name' => 'Rosas de Exportación',
            'slug' => Str::slug('Rosas de Exportación'),
            'description' => 'Rosas premium ecuatorianas de alta calidad.',
            'is_active' => true,
        ]);

        // 2. Agregamos las rosas a esa categoría
        $rosas = [
            ['name' => 'Pink Mondial', 'price' => 45.00, 'stock' => 100],
            ['name' => 'Magic Time', 'price' => 48.50, 'stock' => 80],
            ['name' => 'Quicksand', 'price' => 50.00, 'stock' => 50],
        ];

        foreach ($rosas as $rosa) {
            Product::create([
                'category_id' => $category->id,
                'name' => $rosa['name'],
                'slug' => Str::slug($rosa['name']),
                'description' => 'Hermosa rosa variedad ' . $rosa['name'] . ' ideal para arreglos.',
                'price' => $rosa['price'],
                'stock' => $rosa['stock'],
                'is_active' => true,
            ]);
        }
    }
}
