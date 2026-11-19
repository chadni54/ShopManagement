<?php

namespace Database\Factories;

use App\Domains\Brands\Models\Brand;
use App\Domains\Categories\Models\Category;
use App\Domains\Products\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Product>
 */
class ProductFactory extends Factory
{
    protected $model = Product::class;

    public function definition(): array
    {
        $name = fake()->unique()->words(3, true);
        $price = fake()->randomFloat(2, 5, 500);

        return [
            'category_id' => Category::factory(),
            'brand_id' => Brand::factory(),
            'sku' => 'SKU-'.strtoupper(Str::random(8)),
            'name' => ucwords($name),
            'slug' => Str::slug($name).'-'.Str::lower(Str::random(5)),
            'description' => fake()->paragraph(),
            'price' => $price,
            'compare_at_price' => $price + fake()->randomFloat(2, 1, 50),
            'cost' => max(1, $price * 0.6),
            'currency' => 'USD',
            'is_active' => true,
            'extra_attributes' => ['color' => fake()->safeColorName(), 'size' => fake()->randomElement(['S', 'M', 'L', 'XL'])],
        ];
    }
}
