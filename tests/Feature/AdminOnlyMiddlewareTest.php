<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminOnlyMiddlewareTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test that unauthenticated users receive 401 response.
     * 
     * **Validates: Requirements 1.6**
     */
    public function test_unauthenticated_user_receives_401(): void
    {
        $response = $this->getJson('/api/test-admin');

        $response->assertStatus(401)
            ->assertJson([
                'message' => 'Unauthenticated.'
            ]);
    }

    /**
     * Test that non-admin users receive 403 response.
     * 
     * **Validates: Requirements 7.1**
     */
    public function test_non_admin_user_receives_403(): void
    {
        $user = User::factory()->create([
            'role' => 'user'
        ]);

        $response = $this->actingAs($user)
            ->getJson('/api/test-admin');

        $response->assertStatus(403)
            ->assertJson([
                'message' => 'Forbidden. Admin access required.'
            ]);
    }

    /**
     * Test that admin users can access protected routes.
     * 
     * **Validates: Requirements 7.1**
     */
    public function test_admin_user_can_access_protected_route(): void
    {
        $admin = User::factory()->create([
            'role' => 'admin'
        ]);

        $response = $this->actingAs($admin)
            ->getJson('/api/test-admin');

        $response->assertStatus(200);
    }

    /**
     * Test that middleware returns proper JSON response format.
     * 
     * **Validates: Requirements 1.6**
     */
    public function test_middleware_returns_json_response(): void
    {
        $response = $this->getJson('/api/test-admin');

        $response->assertStatus(401)
            ->assertHeader('Content-Type', 'application/json');
    }
}
