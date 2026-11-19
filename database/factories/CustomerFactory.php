<?php

namespace Database\Factories;

use App\Domains\Customers\Models\Customer;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Customer>
 */
class CustomerFactory extends Factory
{
    protected $model = Customer::class;

    public function definition(): array
    {
        return [
            'user_id' => User::factory()->customer(),
            'customer_number' => 'CUS-'.strtoupper(fake()->unique()->bothify('########')),
            'company_name' => fake()->optional()->company(),
            'billing_address' => fake()->streetAddress(),
            'shipping_address' => fake()->streetAddress(),
            'city' => fake()->city(),
            'state' => fake()->state(),
            'country' => fake()->country(),
            'postal_code' => fake()->postcode(),
            'status' => 'active',
        ];
    }
}
