<?php

namespace Database\Factories;

use App\Domains\Customers\Models\Customer;
use App\Domains\Orders\Models\Order;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Order>
 */
class OrderFactory extends Factory
{
    protected $model = Order::class;

    public function definition(): array
    {
        $subtotal = fake()->randomFloat(2, 20, 800);
        $discount = fake()->randomFloat(2, 0, 20);
        $tax = round($subtotal * 0.08, 2);
        $shipping = fake()->randomFloat(2, 0, 25);
        $total = max(0, $subtotal - $discount + $tax + $shipping);

        return [
            'customer_id' => Customer::factory(),
            'order_number' => 'ORD-'.strtoupper(Str::random(10)),
            'status' => fake()->randomElement(['pending', 'paid', 'processing', 'shipped', 'delivered', 'cancelled']),
            'subtotal' => $subtotal,
            'discount' => $discount,
            'tax' => $tax,
            'shipping_amount' => $shipping,
            'total' => $total,
            'currency' => 'USD',
            'coupon_id' => null,
            'notes' => fake()->optional()->sentence(),
            'placed_at' => fake()->dateTimeBetween('-90 days', 'now'),
        ];
    }
}
