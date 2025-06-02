<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_access_admin_routes()
    {
        $admin = User::factory()->create(['user_type' => 'admin']);
        $this->actingAs($admin, 'sanctum');

        $this->getJson('/api/admin/products')->assertStatus(200);
        $this->getJson('/api/admin/orders')->assertStatus(200);
        $this->getJson('/api/admin/users')->assertStatus(200);
    }

    public function test_non_admin_cannot_access_admin_routes()
    {
        $user = User::factory()->create(['user_type' => 'user']);
        $this->actingAs($user, 'sanctum');

        $this->getJson('/api/admin/products')->assertStatus(403);
    }

    public function test_guest_cannot_access_admin_routes()
    {
        $this->getJson('/api/admin/products')->assertStatus(401);
    }
}
