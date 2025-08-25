<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Sale>
 */
class SaleFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $subtotal = fake()->randomFloat(2, 50, 2000);
        $taxRate = 0.15; // 15% tax
        $taxAmount = $subtotal * $taxRate;
        $discountAmount = fake()->randomFloat(2, 0, $subtotal * 0.2); // Up to 20% discount
        $totalAmount = $subtotal + $taxAmount - $discountAmount;
        $amountPaid = $totalAmount + fake()->randomFloat(2, 0, 50); // May include tip or rounded up
        $changeAmount = $amountPaid - $totalAmount;

        return [
            'user_id' => User::factory(),
            'subtotal' => $subtotal,
            'tax_amount' => $taxAmount,
            'discount_amount' => $discountAmount,
            'total_amount' => $totalAmount,
            'amount_paid' => $amountPaid,
            'change_amount' => $changeAmount,
            'payment_method' => fake()->randomElement(['cash', 'card', 'mobile_money']),
            'status' => fake()->randomElement(['pending', 'completed', 'cancelled']),
            'notes' => fake()->optional()->sentence(),
        ];
    }

    /**
     * Create a completed sale
     */
    public function completed(): static
    {
        return $this->state(function (array $attributes) {
            return [
                'status' => 'completed',
            ];
        });
    }

    /**
     * Create a pending sale
     */
    public function pending(): static
    {
        return $this->state(function (array $attributes) {
            return [
                'status' => 'pending',
            ];
        });
    }

    /**
     * Create a cancelled sale
     */
    public function cancelled(): static
    {
        return $this->state(function (array $attributes) {
            return [
                'status' => 'cancelled',
                'amount_paid' => 0,
                'change_amount' => 0,
            ];
        });
    }
}
