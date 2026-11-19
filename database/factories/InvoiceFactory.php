<?php

namespace Database\Factories;

use App\Domains\Invoices\Models\Invoice;
use App\Domains\Orders\Models\Order;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Invoice>
 */
class InvoiceFactory extends Factory
{
    protected $model = Invoice::class;

    public function definition(): array
    {
        $subtotal = fake()->randomFloat(2, 20, 800);
        $tax = round($subtotal * 0.08, 2);

        return [
            'order_id' => Order::factory(),
            'invoice_number' => 'INV-'.strtoupper(Str::random(10)),
            'status' => fake()->randomElement(['draft', 'issued', 'paid', 'void']),
            'subtotal' => $subtotal,
            'tax' => $tax,
            'total' => $subtotal + $tax,
            'issued_at' => now(),
            'due_at' => now()->addDays(30),
            'pdf_path' => null,
        ];
    }
}
