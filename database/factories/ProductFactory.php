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
        return [
            'name' => $this->faker->unique()->word . ' Sunglasses',
            'description' => $this->faker->sentence,
            'price' => $this->faker->randomFloat(2, 100, 500),
            'image' => $this->faker->imageUrl(400, 400, 'fashion'),
            'category_id' => \App\Models\Category::factory(),
        ];
    }

}
