<?php

namespace Database\Factories;

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
            'user_id' => \App\Models\User::inRandomOrder()->first()->id,
            'status' => $this->faker->randomElement(['pending', 'confirmed', 'canceled']),
            'total' => $this->faker->randomFloat(2, 100, 1000),
            'fake_payment_info' => $this->faker->creditCardNumber(),
        ];
    }
}
