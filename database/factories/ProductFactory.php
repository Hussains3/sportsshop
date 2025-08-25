<?php

namespace Database\Factories;

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
            'name' => $name,
            'slug' => str()->slug($name),
            'description' => fake()->paragraphs(2, true),
            'sku' => fake()->unique()->ean8(),
            'is_active' => fake()->boolean(80),
            'is_featured' => fake()->boolean(20),
        ];
    }
}
