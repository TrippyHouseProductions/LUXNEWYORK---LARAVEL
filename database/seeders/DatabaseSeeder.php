<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Models\User;
use App\Models\Category;
use App\Models\Product;
use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Wishlist;
use App\Models\WishlistItem;
use App\Models\Order;
use App\Models\OrderItem;

class DatabaseSeeder extends Seeder
{
    protected static ?string $password;

    public function run(): void
    {
        // Create one admin
        $admin = User::factory()->create([
            'name' => 'Admin User',
            'email' => 'admin@luxnewyork.com',
            'email_verified_at' => now(),
            'password' => static::$password ??= Hash::make('password'),
            'user_type' => 'admin',
            'two_factor_secret' => null,
            'two_factor_recovery_codes' => null,
            'remember_token' => Str::random(10),
            'profile_photo_path' => null,
            'current_team_id' => null,
        ]);

        // Customers
        $customers = User::factory(5)->create();

        // Categories
        $categories = Category::factory(3)->create();

        // Products with random category for each
        $products = Product::factory(15)->create();

        // Carts, Wishlists, Orders, and their items for each customer
        foreach ($customers as $customer) {
            // Cart
            $cart = Cart::factory()->create(['user_id' => $customer->id]);
            // Cart Items
            CartItem::factory(2)->create([
                'cart_id' => $cart->id,
                'product_id' => $products->random()->id,
            ]);

            // Wishlist
            $wishlist = Wishlist::factory()->create(['user_id' => $customer->id]);
            // Wishlist Items
            WishlistItem::factory(2)->create([
                'wishlist_id' => $wishlist->id,
                'product_id' => $products->random()->id,
            ]);

            // Order
            $order = Order::factory()->create(['user_id' => $customer->id]);
            // Order Items
            OrderItem::factory(2)->create([
                'order_id' => $order->id,
                'product_id' => $products->random()->id,
            ]);
        }
    }
}
