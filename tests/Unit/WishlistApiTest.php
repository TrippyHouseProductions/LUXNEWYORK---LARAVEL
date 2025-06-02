<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class WishlistApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_wishlist_crud_flow()
    {
        $user = User::factory()->create();
        $product = Product::factory()->create();

        $this->actingAs($user, 'sanctum');

        // Add to wishlist
        $this->postJson('/api/wishlist', [
            'product_id' => $product->id,
        ])->assertStatus(201);

        // View wishlist
        $this->getJson('/api/wishlist')
            ->assertStatus(200)
            ->assertJsonFragment(['product_id' => $product->id]);

        // Remove from wishlist
        $itemId = $user->wishlist->items()->first()->id;
        $this->deleteJson("/api/wishlist/{$itemId}")->assertStatus(200);
    }
}
