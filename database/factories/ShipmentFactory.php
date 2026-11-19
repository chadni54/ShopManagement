<?php

namespace Database\Factories;

use App\Domains\Orders\Models\Order;
use App\Domains\Shipping\Models\Shipment;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Shipment>
 */
class ShipmentFactory extends Factory
{
    protected $model = Shipment::class;

    public function definition(): array
    {
        return [
            'order_id' => Order::factory(),
            'carrier' => fake()->randomElement(['UPS', 'FedEx', 'DHL', 'USPS']),
            'tracking_number' => strtoupper(Str::random(12)),
            'status' => fake()->randomElement(['pending', 'shipped', 'in_transit', 'delivered']),
            'shipped_at' => fake()->optional()->dateTimeBetween('-30 days', 'now'),
            'delivered_at' => fake()->optional()->dateTimeBetween('-10 days', 'now'),
            'address' => [
                'line1' => fake()->streetAddress(),
                'city' => fake()->city(),
                'state' => fake()->stateAbbr(),
                'postal_code' => fake()->postcode(),
                'country' => 'US',
            ],
        ];
    }
}
