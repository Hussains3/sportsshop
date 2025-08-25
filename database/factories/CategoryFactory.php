<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Category>
 */
class CategoryFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $name = fake()->unique()->randomElement([
            'Football',
            'Basketball',
            'Tennis',
            'Running',
            'Swimming',
            'Fitness Equipment',
            'Team Sports',
            'Outdoor Sports',
            'Winter Sports',
            'Combat Sports'
        ]);

        return [
            'name' => $name,
            'slug' => str()->slug($name),
            'description' => fake()->paragraph(),
            'is_active' => true,
        ];
    }
}
