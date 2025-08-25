<?php

namespace Database\Factories;

use App\Models\Category;
use App\Models\SubCategory;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\SubCategory>
 */
class SubCategoryFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $subcategoriesByCategory = [
            'Football' => ['Soccer Balls', 'Football Boots', 'Shin Guards', 'Goal Keeper Gloves'],
            'Basketball' => ['Basketballs', 'Basketball Shoes', 'Hoops', 'Basketball Accessories'],
            'Tennis' => ['Tennis Rackets', 'Tennis Balls', 'Tennis Shoes', 'Tennis Bags'],
            'Running' => ['Running Shoes', 'Running Clothes', 'Fitness Trackers', 'Hydration Packs'],
            'Swimming' => ['Swimsuits', 'Goggles', 'Swim Caps', 'Swimming Accessories'],
            'Fitness Equipment' => ['Dumbbells', 'Yoga Mats', 'Resistance Bands', 'Exercise Bikes'],
            'Team Sports' => ['Team Uniforms', 'Training Cones', 'Whistles', 'Score Boards'],
            'Outdoor Sports' => ['Camping Gear', 'Hiking Boots', 'Backpacks', 'Climbing Equipment'],
            'Winter Sports' => ['Skis', 'Snowboards', 'Winter Boots', 'Thermal Wear'],
            'Combat Sports' => ['Boxing Gloves', 'Punching Bags', 'Protective Gear', 'Training Gear']
        ];

        // Select a random subcategory name from all available options
        $allSubcategories = array_merge(...array_values($subcategoriesByCategory));
        $name = fake()->unique(true)->randomElement($allSubcategories);
        $slug = str()->slug($name);

        return [
            'category_id' => Category::factory(),
            'name' => $name,
            'slug' => $slug,
            'description' => fake()->paragraph(),
            'is_active' => true,
        ];
    }
}
