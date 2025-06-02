<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Product;
use App\Models\Cart;
use App\Models\CartItem;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class OrderApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_order_placement_and_listing()
    {
        $user = User::factory()->create();
        $product = Product::factory()->create(['stock' => 10]);
        $this->actingAs($user, 'sanctum');

        // Add to cart first
        $cart = Cart::factory()->create(['user_id' => $user->id]);
        CartItem::factory()->create([
            'cart_id' => $cart->id,
            'product_id' => $product->id,
            'quantity' => 2
        ]);

        // Place order
        $this->postJson('/api/orders', [
            // You may need additional fields depending on your logic
        ])->assertStatus(201);

        // List orders
        $this->getJson('/api/orders')->assertStatus(200);
    }
}
