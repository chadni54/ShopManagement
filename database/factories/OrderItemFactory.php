<?php

namespace Database\Factories;

use App\Domains\Orders\Models\Order;
use App\Domains\Orders\Models\OrderItem;
use App\Domains\Products\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<OrderItem>
 */
class OrderItemFactory extends Factory
{
    protected $model = OrderItem::class;

    public function definition(): array
    {
        $qty = fake()->numberBetween(1, 5);
        $unit = fake()->randomFloat(2, 5, 200);

        return [
            'order_id' => Order::factory(),
            'product_id' => Product::factory(),
            'sku' => 'SKU-'.fake()->bothify('########'),
            'name' => fake()->words(3, true),
            'quantity' => $qty,
            'unit_price' => $unit,
            'total' => round($unit * $qty, 2),
        ];
    }
}
