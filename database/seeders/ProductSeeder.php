<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get all subcategories
        $subcategories = \App\Models\SubCategory::all();

        // Create 5-10 products for each subcategory
        $subcategories->each(function ($subcategory) {
            \App\Models\Product::factory()
                ->count(fake()->numberBetween(5, 10))
                ->create([
                    'sub_category_id' => $subcategory->id,
                ])
                ->each(function ($product) {
                    // Create 1-3 batches for each product
                    \App\Models\Batch::factory()
                        ->count(fake()->numberBetween(1, 3))
                        ->create([
                            'product_id' => $product->id,
                        ]);
                });
        });
    }
}
