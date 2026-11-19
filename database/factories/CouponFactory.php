<?php

namespace Database\Factories;

use App\Domains\Coupons\Models\Coupon;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Coupon>
 */
class CouponFactory extends Factory
{
    protected $model = Coupon::class;

    public function definition(): array
    {
        $type = fake()->randomElement(['percent', 'fixed']);

        return [
            'code' => strtoupper(Str::random(8)),
            'type' => $type,
            'value' => $type === 'percent' ? fake()->numberBetween(5, 30) : fake()->randomFloat(2, 5, 50),
            'min_order_amount' => fake()->optional()->randomFloat(2, 20, 100),
            'max_uses' => fake()->optional()->numberBetween(10, 500),
            'used_count' => 0,
            'starts_at' => now()->subDays(7),
            'ends_at' => now()->addDays(60),
            'is_active' => true,
        ];
    }
}
