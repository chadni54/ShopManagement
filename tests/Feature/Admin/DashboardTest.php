<?php

namespace Tests\Feature\Admin;

use App\Admin\ResourceCatalog;
use App\Domains\Categories\Models\Category;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DashboardTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_is_sent_to_login(): void
    {
        $this->get('/dashboard')->assertRedirect(route('login'));
        $this->get('/login')->assertOk()->assertSee('ShopDemo');
    }

    public function test_dashboard_uses_every_api_resource(): void
    {
        $admin = User::factory()->admin()->create([
            'email' => 'admin@shopdemo.test',
            'password' => 'password',
            'status' => 'active',
        ]);

        $this->post('/login', [
            'email' => 'admin@shopdemo.test',
            'password' => 'password',
        ])->assertRedirect(route('dashboard'));

        $this->get('/dashboard')
            ->assertOk()
            ->assertSee('Dashboard')
            ->assertSee($admin->email);

        foreach (ResourceCatalog::keys() as $key) {
            $this->get('/dashboard/'.$key)->assertOk();
            $this->get('/dashboard/'.$key.'/create')->assertOk();
        }

        $this->post('/dashboard/categories', [
            'name' => 'Outdoor',
            'slug' => 'outdoor',
            'is_active' => '1',
        ])->assertRedirect();

        $category = Category::query()->where('slug', 'outdoor')->firstOrFail();

        $this->get('/dashboard/categories')->assertOk()->assertSee('Outdoor');
        $this->get('/dashboard/categories/'.$category->uuid)->assertOk()->assertSee('outdoor');
        $this->get('/dashboard/categories/'.$category->uuid.'/edit')->assertOk()->assertSee('outdoor');
        $this->put('/dashboard/categories/'.$category->uuid, [
            'name' => 'Outdoor Gear',
        ])->assertRedirect();
        $this->get('/dashboard/categories/'.$category->uuid)->assertOk()->assertSee('Outdoor Gear');
        $this->delete('/dashboard/categories/'.$category->uuid)->assertRedirect('/dashboard/categories');

        $this->put('/dashboard/settings', [
            'enable_notifications' => '1',
            'enable_email' => '0',
            'enable_push' => '0',
            'language' => 'en',
            'timezone' => 'UTC',
            'currency' => 'USD',
            'preferences' => '{"theme":"paper"}',
        ])->assertRedirect(route('admin.settings'));

        $this->get('/dashboard/settings')->assertOk()->assertSee('Settings');
        $this->get('/dashboard/notifications')->assertOk()->assertSee('Notifications');
        $this->get('/dashboard/profile')->assertOk()->assertSee($admin->email);
    }

    public function test_api_accepts_uuid_foreign_keys(): void
    {
        $admin = User::factory()->admin()->create();
        $category = Category::factory()->create();

        $this->actingAs($admin, 'sanctum')
            ->postJson('/api/v1/products', [
                'category_id' => $category->uuid,
                'sku' => 'SKU-UUID-1',
                'name' => 'UUID Product',
                'slug' => 'uuid-product',
                'price' => 12.5,
            ])
            ->assertCreated()
            ->assertJsonPath('data.sku', 'SKU-UUID-1');

        $this->assertDatabaseHas('products', [
            'sku' => 'SKU-UUID-1',
            'category_id' => $category->id,
        ]);
    }
}
