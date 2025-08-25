<?php

namespace Database\Factories;

use App\Models\Product;
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
        $initialQuantity = fake()->numberBetween(50, 200);

        return [
            'product_id' => Product::factory(),
            'batch_number' => fake()->unique()->regexify('[A-Z]{2}[0-9]{6}'),
            'initial_quantity' => $initialQuantity,
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

    /**
     * Create a batch with stock available
     */
    public function inStock(): static
    {
        return $this->state(function (array $attributes) {
            return [
                'current_quantity' => fake()->numberBetween(1, $attributes['initial_quantity']),
            ];
        });
    }

    /**
     * Create a batch that's out of stock
     */
    public function outOfStock(): static
    {
        return $this->state(function (array $attributes) {
            return [
                'current_quantity' => 0,
            ];
        });
    }
}
