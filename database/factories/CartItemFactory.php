<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\CartItem>
 */
class CartItemFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'cart_id' => \App\Models\Cart::inRandomOrder()->first()->id,
            'product_id' => \App\Models\Product::inRandomOrder()->first()->id,
            'quantity' => $this->faker->numberBetween(1, 3),
        ];
    }
}
