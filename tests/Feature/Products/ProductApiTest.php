<?php

namespace Tests\Feature\Products;

use App\Domains\Brands\Models\Brand;
use App\Domains\Categories\Models\Category;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class ProductApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_list_create_show_update_and_delete_products(): void
    {
        $admin = User::factory()->admin()->create();
        Sanctum::actingAs($admin);

        $category = Category::factory()->create();
        $brand = Brand::factory()->create();

        $create = $this->postJson('/api/v1/products', [
            'category_id' => $category->id,
            'brand_id' => $brand->id,
            'sku' => 'SKU-TEST-001',
            'name' => 'Demo Product',
            'slug' => 'demo-product',
            'description' => 'A demo product',
            'price' => 29.99,
            'currency' => 'USD',
            'is_active' => true,
            'attributes' => ['color' => 'blue'],
        ]);

        $create->assertCreated()
            ->assertJsonPath('success', true)
            ->assertJsonPath('data.sku', 'SKU-TEST-001')
            ->assertJsonPath('data.attributes.color', 'blue');

        $uuid = $create->json('data.id');

        $this->getJson('/api/v1/products')
            ->assertOk()
            ->assertJsonPath('success', true);

        $this->getJson("/api/v1/products/{$uuid}")
            ->assertOk()
            ->assertJsonPath('data.id', $uuid);

        $this->putJson("/api/v1/products/{$uuid}", [
            'price' => 39.99,
        ])->assertOk()
            ->assertJsonPath('data.price', '39.99');

        $this->deleteJson("/api/v1/products/{$uuid}")
            ->assertOk()
            ->assertJsonPath('success', true);

        $this->assertSoftDeleted('products', [
            'sku' => 'SKU-TEST-001',
        ]);
    }

    public function test_products_require_authentication(): void
    {
        $this->getJson('/api/v1/products')->assertUnauthorized();
    }
}
