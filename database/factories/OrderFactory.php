<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Order>
 */
class OrderFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'order_number' => 'ORD-' . now()->format('Ymd') . '-' . str_pad(fake()->numberBetween(0, 99999), 5, '0', STR_PAD_LEFT),
            'total_amount' => fake()->randomFloat(2, 10, 500),
            'status' => fake()->randomElement(['pending', 'processing', 'completed', 'cancelled']),
            'customer_name' => fake()->name(),
            'customer_email' => fake()->safeEmail(),
            'customer_phone' => fake()->phoneNumber(),
            'delivery_address' => fake()->address(),
        ];
    }
}
