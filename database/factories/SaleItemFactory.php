<?php

namespace Database\Factories;

use App\Models\Sale;
use App\Models\Product;
use App\Models\Batch;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\SaleItem>
 */
class SaleItemFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $quantity = fake()->numberBetween(1, 10);
        $unitPrice = fake()->randomFloat(2, 10, 500);
        $discountAmount = fake()->randomFloat(2, 0, $unitPrice * $quantity * 0.15); // Up to 15% discount per item
        $subtotal = ($unitPrice * $quantity) - $discountAmount;

        return [
            'sale_id' => Sale::factory(),
            'product_id' => Product::factory(),
            'batch_id' => Batch::factory(),
            'quantity' => $quantity,
            'unit_price' => $unitPrice,
            'discount_amount' => $discountAmount,
            'subtotal' => $subtotal,
        ];
    }

    /**
     * Create sale item with no discount
     */
    public function noDiscount(): static
    {
        return $this->state(function (array $attributes) {
            $subtotal = $attributes['unit_price'] * $attributes['quantity'];
            return [
                'discount_amount' => 0,
                'subtotal' => $subtotal,
            ];
        });
    }

    /**
     * Create sale item with high discount
     */
    public function highDiscount(): static
    {
        return $this->state(function (array $attributes) {
            $discountAmount = ($attributes['unit_price'] * $attributes['quantity']) * 0.25; // 25% discount
            $subtotal = ($attributes['unit_price'] * $attributes['quantity']) - $discountAmount;
            return [
                'discount_amount' => $discountAmount,
                'subtotal' => $subtotal,
            ];
        });
    }
}
