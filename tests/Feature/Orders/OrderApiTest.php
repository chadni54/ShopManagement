<?php

namespace Tests\Feature\Orders;

use App\Domains\Customers\Models\Customer;
use App\Domains\Products\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class OrderApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_create_show_update_cancel_and_delete_orders(): void
    {
        $admin = User::factory()->admin()->create();
        Sanctum::actingAs($admin);

        $customer = Customer::factory()->create();
        $product = Product::factory()->create(['price' => 25.00]);

        $create = $this->postJson('/api/v1/orders', [
            'customer_id' => $customer->id,
            'tax' => 2.00,
            'shipping_amount' => 5.00,
            'items' => [
                [
                    'product_id' => $product->id,
                    'quantity' => 2,
                ],
            ],
        ]);

        $create->assertCreated()
            ->assertJsonPath('success', true)
            ->assertJsonPath('data.status', 'pending')
            ->assertJsonPath('data.subtotal', '50.00')
            ->assertJsonPath('data.total', '57.00');

        $uuid = $create->json('data.id');

        $this->getJson("/api/v1/orders/{$uuid}")
            ->assertOk()
            ->assertJsonPath('data.id', $uuid)
            ->assertJsonStructure(['data' => ['items']]);

        $this->putJson("/api/v1/orders/{$uuid}", [
            'status' => 'paid',
        ])->assertOk()
            ->assertJsonPath('data.status', 'paid');

        $this->postJson("/api/v1/orders/{$uuid}/cancel", [
            'reason' => 'Customer requested',
        ])->assertOk()
            ->assertJsonPath('data.status', 'cancelled');

        $this->deleteJson("/api/v1/orders/{$uuid}")
            ->assertOk()
            ->assertJsonPath('success', true);

        $this->assertSoftDeleted('orders', [
            'uuid' => $uuid,
        ]);
    }

    public function test_orders_require_authentication(): void
    {
        $this->getJson('/api/v1/orders')->assertUnauthorized();
    }
}
