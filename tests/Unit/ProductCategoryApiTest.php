<?php

namespace Tests\Feature;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProductCategoryApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_product_list_and_show()
    {
        $product = Product::factory()->create();

        $this->getJson('/api/products')
            ->assertStatus(200)
            ->assertJsonFragment(['id' => $product->id]);

        $this->getJson('/api/products/' . $product->id)
            ->assertStatus(200)
            ->assertJsonFragment(['id' => $product->id]);
    }

    public function test_category_list_and_show()
    {
        $category = Category::factory()->create();

        $this->getJson('/api/categories')
            ->assertStatus(200)
            ->assertJsonFragment(['id' => $category->id]);

        $this->getJson('/api/categories/' . $category->id)
            ->assertStatus(200)
            ->assertJsonFragment(['id' => $category->id]);
    }
}
