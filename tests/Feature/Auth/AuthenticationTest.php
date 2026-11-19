<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class AuthenticationTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_login_with_valid_credentials(): void
    {
        $user = User::factory()->create([
            'email' => 'login@shopdemo.test',
            'password' => 'password',
            'status' => 'active',
        ]);

        $response = $this->postJson('/api/v1/login', [
            'email' => 'login@shopdemo.test',
            'password' => 'password',
        ]);

        $response->assertOk()
            ->assertJsonPath('success', true)
            ->assertJsonStructure([
                'success',
                'message',
                'data' => ['token', 'token_type', 'user'],
            ]);
    }

    public function test_login_fails_with_invalid_credentials(): void
    {
        User::factory()->create([
            'email' => 'login@shopdemo.test',
            'password' => 'password',
        ]);

        $response = $this->postJson('/api/v1/login', [
            'email' => 'login@shopdemo.test',
            'password' => 'wrong-password',
        ]);

        $response->assertStatus(422)
            ->assertJsonPath('success', false)
            ->assertJsonPath('message', 'Validation Failed');
    }

    public function test_authenticated_user_can_view_and_update_profile(): void
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        $this->getJson('/api/v1/profile')
            ->assertOk()
            ->assertJsonPath('data.email', $user->email);

        $this->putJson('/api/v1/profile', [
            'first_name' => 'Updated',
            'last_name' => 'Name',
        ])->assertOk()
            ->assertJsonPath('data.first_name', 'Updated');
    }

    public function test_user_can_logout(): void
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        $this->postJson('/api/v1/logout')
            ->assertOk()
            ->assertJsonPath('success', true);
    }
}
