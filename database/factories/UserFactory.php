<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends Factory<User>
 */
class UserFactory extends Factory
{
    protected static ?string $password;

    protected $model = User::class;

    public function definition(): array
    {
        $first = fake()->firstName();
        $last = fake()->lastName();

        return [
            'name' => "{$first} {$last}",
            'first_name' => $first,
            'last_name' => $last,
            'email' => fake()->unique()->safeEmail(),
            'phone' => fake()->numerify('+1555#######'),
            'email_verified_at' => now(),
            'password' => static::$password ??= Hash::make('password'),
            'role' => fake()->randomElement(['admin', 'staff', 'customer']),
            'status' => 'active',
            'remember_token' => Str::random(10),
        ];
    }

    public function admin(): static
    {
        return $this->state(fn () => ['role' => 'admin']);
    }

    public function staff(): static
    {
        return $this->state(fn () => ['role' => 'staff']);
    }

    public function customer(): static
    {
        return $this->state(fn () => ['role' => 'customer']);
    }

    public function unverified(): static
    {
        return $this->state(fn () => [
            'email_verified_at' => null,
        ]);
    }
}
