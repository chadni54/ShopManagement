<?php

namespace Database\Factories;

use App\Domains\Customers\Models\Customer;
use App\Domains\Products\Models\Product;
use App\Domains\Reviews\Models\Review;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Review>
 */
class ReviewFactory extends Factory
{
    protected $model = Review::class;

    public function definition(): array
    {
        return [
            'product_id' => Product::factory(),
            'customer_id' => Customer::factory(),
            'rating' => fake()->numberBetween(1, 5),
            'title' => fake()->sentence(4),
            'body' => fake()->paragraph(),
            'is_approved' => fake()->boolean(70),
            'reviewed_at' => fake()->dateTimeBetween('-60 days', 'now'),
        ];
    }
}
