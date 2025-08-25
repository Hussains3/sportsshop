<?php

namespace Database\Factories;

use App\Models\SubCategory;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
 */
class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $name = fake()->unique()->words(3, true);
        return [
            'sub_category_id' => SubCategory::factory(),
            'name' => $name,
            'slug' => str()->slug($name),
            'description' => fake()->paragraphs(2, true),
            'sku' => fake()->unique()->ean8(),
            'image' => fake()->optional()->imageUrl(640, 480, 'sports', true),
            'is_active' => fake()->boolean(80),
            'is_featured' => fake()->boolean(20),
        ];
    }

    /**
     * Create an active product
     */
    public function active(): static
    {
        return $this->state(function (array $attributes) {
            return [
                'is_active' => true,
            ];
        });
    }

    /**
     * Create a featured product
     */
    public function featured(): static
    {
        return $this->state(function (array $attributes) {
            return [
                'is_featured' => true,
                'is_active' => true,
            ];
        });
    }
}
