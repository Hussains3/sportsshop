<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Batch>
 */
class BatchFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $purchase_price = fake()->randomFloat(2, 5, 500);
        $min_selling_price = $purchase_price * 1.2; // 20% minimum markup
        $max_selling_price = $min_selling_price * 1.5; // Up to 50% more than min price

        return [
            'batch_number' => fake()->unique()->regexify('[A-Z]{2}[0-9]{6}'),
            'initial_quantity' => fake()->numberBetween(50, 200),
            'current_quantity' => function (array $attributes) {
                return fake()->numberBetween(0, $attributes['initial_quantity']);
            },
            'purchase_price' => $purchase_price,
            'min_selling_price' => $min_selling_price,
            'max_selling_price' => $max_selling_price,
            'purchase_date' => fake()->dateTimeBetween('-6 months', 'now'),
            'notes' => fake()->optional()->sentence(),
        ];
    }
}
