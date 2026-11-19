<?php

namespace Database\Factories;

use App\Domains\Inventory\Models\InventoryItem;
use App\Domains\Products\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<InventoryItem>
 */
class InventoryItemFactory extends Factory
{
    protected $model = InventoryItem::class;

    public function definition(): array
    {
        $qty = fake()->numberBetween(0, 200);

        return [
            'product_id' => Product::factory(),
            'quantity' => $qty,
            'reserved_quantity' => fake()->numberBetween(0, min(10, $qty)),
            'warehouse' => fake()->randomElement(['main', 'east', 'west']),
            'low_stock_threshold' => 5,
            'status' => $qty <= 0 ? 'out_of_stock' : ($qty <= 5 ? 'low_stock' : 'in_stock'),
        ];
    }
}
