<?php

namespace Database\Factories;

use App\Domains\Orders\Models\Order;
use App\Domains\Payments\Models\Payment;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Payment>
 */
class PaymentFactory extends Factory
{
    protected $model = Payment::class;

    public function definition(): array
    {
        $status = fake()->randomElement(['pending', 'completed', 'failed', 'refunded']);

        return [
            'order_id' => Order::factory(),
            'payment_number' => 'PAY-'.strtoupper(Str::random(10)),
            'method' => fake()->randomElement(['card', 'paypal', 'bank_transfer', 'cod']),
            'amount' => fake()->randomFloat(2, 10, 1000),
            'currency' => 'USD',
            'status' => $status,
            'provider_reference' => strtoupper(Str::random(12)),
            'paid_at' => $status === 'completed' ? now() : null,
        ];
    }
}
