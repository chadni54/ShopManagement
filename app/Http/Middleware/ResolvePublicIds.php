<?php

namespace App\Http\Middleware;

use App\Domains\Brands\Models\Brand;
use App\Domains\Categories\Models\Category;
use App\Domains\Coupons\Models\Coupon;
use App\Domains\Customers\Models\Customer;
use App\Domains\Orders\Models\Order;
use App\Domains\Products\Models\Product;
use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\Response;

class ResolvePublicIds
{
    /**
     * The API exposes UUIDs as id, while foreign-key rules still check numeric ids.
     * Accept either form so the dashboard can submit the ids the API returns.
     *
     * @var array<string, class-string>
     */
    private array $keys = [
        'user_id' => User::class,
        'customer_id' => Customer::class,
        'category_id' => Category::class,
        'brand_id' => Brand::class,
        'parent_id' => Category::class,
        'product_id' => Product::class,
        'coupon_id' => Coupon::class,
        'order_id' => Order::class,
    ];

    public function handle(Request $request, Closure $next): Response
    {
        $input = $request->all();
        $changed = false;

        foreach ($this->keys as $key => $model) {
            if (! array_key_exists($key, $input)) {
                continue;
            }

            $resolved = $this->resolve($model, $input[$key]);

            if ($resolved !== null && $resolved !== $input[$key]) {
                $input[$key] = $resolved;
                $changed = true;
            }
        }

        if (isset($input['items']) && is_array($input['items'])) {
            foreach ($input['items'] as $index => $item) {
                if (! is_array($item) || ! array_key_exists('product_id', $item)) {
                    continue;
                }

                $resolved = $this->resolve(Product::class, $item['product_id']);

                if ($resolved !== null && $resolved !== $item['product_id']) {
                    $input['items'][$index]['product_id'] = $resolved;
                    $changed = true;
                }
            }
        }

        if ($changed) {
            $request->merge($input);
        }

        return $next($request);
    }

    private function resolve(string $model, mixed $value): mixed
    {
        if (! is_string($value) || ! Str::isUuid($value)) {
            return null;
        }

        $id = $model::query()->where('uuid', $value)->value('id');

        return $id ?: null;
    }
}
