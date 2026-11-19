<?php

namespace Tests\Feature\Customers;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class CustomerApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_list_create_show_update_and_delete_customers(): void
    {
        $admin = User::factory()->admin()->create();
        Sanctum::actingAs($admin);

        $customerUser = User::factory()->customer()->create();

        $create = $this->postJson('/api/v1/customers', [
            'user_id' => $customerUser->id,
            'customer_number' => 'CUS-TEST-001',
            'company_name' => 'Acme Corp',
            'city' => 'Austin',
            'country' => 'US',
            'status' => 'active',
        ]);

        $create->assertCreated()
            ->assertJsonPath('success', true)
            ->assertJsonPath('data.customer_number', 'CUS-TEST-001');

        $uuid = $create->json('data.id');

        $this->getJson('/api/v1/customers')
            ->assertOk()
            ->assertJsonPath('success', true);

        $this->getJson("/api/v1/customers/{$uuid}")
            ->assertOk()
            ->assertJsonPath('data.id', $uuid);

        $this->putJson("/api/v1/customers/{$uuid}", [
            'city' => 'Dallas',
        ])->assertOk()
            ->assertJsonPath('data.city', 'Dallas');

        $this->deleteJson("/api/v1/customers/{$uuid}")
            ->assertOk()
            ->assertJsonPath('success', true);

        $this->assertSoftDeleted('customers', [
            'customer_number' => 'CUS-TEST-001',
        ]);
    }

    public function test_customers_require_authentication(): void
    {
        $this->getJson('/api/v1/customers')->assertUnauthorized();
    }
}
