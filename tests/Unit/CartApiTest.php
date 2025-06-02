<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CartApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_cart_crud_flow()
    {
        $user = User::factory()->create();
        $product = Product::factory()->create(['stock' => 5]);

        $this->actingAs($user, 'sanctum');

        // Add to cart
        $this->postJson('/api/cart', [
            'product_id' => $product->id,
            'quantity' => 2
        ])->assertStatus(201);

        // View cart
        $this->getJson('/api/cart')
            ->assertStatus(200)
            ->assertJsonFragment(['product_id' => $product->id]);

        // Update quantity
        $itemId = $user->cart->items()->first()->id;
        $this->putJson("/api/cart/{$itemId}", ['quantity' => 3])
            ->assertStatus(200)
            ->assertJsonFragment(['quantity' => 3]);

        // Remove item
        $this->deleteJson("/api/cart/{$itemId}")->assertStatus(200);

        // Clear cart
        $this->deleteJson('/api/cart')->assertStatus(200);
    }
}
