<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_login_and_logout_flow()
    {
        $user = User::factory()->create(['password' => bcrypt('secret')]);

        $response = $this->postJson('/api/login', [
            'email' => $user->email,
            'password' => 'secret',
        ]);
        $response->assertStatus(200)->assertJsonStructure(['token']);

        $token = $response->json('token');
        $this->withHeader('Authorization', 'Bearer ' . $token)
             ->postJson('/api/logout')
             ->assertStatus(200);
    }

    public function test_protected_route_requires_auth()
    {
        $this->getJson('/api/cart')->assertStatus(401);
    }
}
