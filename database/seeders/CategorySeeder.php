<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            'Football',
            'Basketball',
            'Tennis',
            'Running',
            'Swimming',
            'Fitness Equipment',
            'Team Sports',
            'Outdoor Sports',
            'Winter Sports'
        ];

        foreach ($categories as $categoryName) {
            Category::create([
                'name' => $categoryName,
                'slug' => strtolower(str_replace(' ', '-', $categoryName)),
                'description' => fake()->paragraph(),
                'is_active' => true
            ]);
        }
    }
}
