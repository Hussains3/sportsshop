<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\SubCategory;
use Illuminate\Database\Seeder;

class SubCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $subcategories = [
            'Football' => ['Soccer Balls', 'Football Boots', 'Shin Guards', 'Goal Keeper Gloves'],
            'Basketball' => ['Basketballs', 'Basketball Shoes', 'Hoops', 'Basketball Accessories'],
            'Tennis' => ['Tennis Rackets', 'Tennis Balls', 'Tennis Shoes', 'Tennis Bags'],
            'Running' => ['Running Shoes', 'Running Clothes', 'Fitness Trackers', 'Hydration Packs'],
            'Swimming' => ['Swimsuits', 'Goggles', 'Swim Caps', 'Swimming Accessories'],
            'Fitness Equipment' => ['Dumbbells', 'Yoga Mats', 'Resistance Bands', 'Exercise Bikes'],
            'Team Sports' => ['Team Uniforms', 'Training Cones', 'Whistles', 'Score Boards'],
            'Outdoor Sports' => ['Camping Gear', 'Hiking Boots', 'Backpacks', 'Climbing Equipment'],
            'Winter Sports' => ['Skis', 'Snowboards', 'Winter Boots', 'Thermal Wear']
        ];

        foreach ($subcategories as $categoryName => $subCategories) {
            $category = Category::where('name', $categoryName)->first();

            if ($category) {
                foreach ($subCategories as $subCategoryName) {
                    SubCategory::create([
                        'category_id' => $category->id,
                        'name' => $subCategoryName,
                        'slug' => strtolower(str_replace(' ', '-', $subCategoryName)),
                        'description' => fake()->paragraph(),
                        'is_active' => true
                    ]);
                }
            }
        }
    }
}
