<?php

namespace Database\Seeders;

use App\Domains\Brands\Models\Brand;
use App\Domains\Categories\Models\Category;
use App\Domains\Coupons\Models\Coupon;
use App\Domains\Customers\Models\Customer;
use App\Domains\Inventory\Models\InventoryItem;
use App\Domains\Invoices\Models\Invoice;
use App\Domains\Orders\Models\Order;
use App\Domains\Payments\Models\Payment;
use App\Domains\Products\Models\Product;
use App\Domains\Reviews\Models\Review;
use App\Domains\Settings\Models\Setting;
use App\Domains\Shipping\Models\Shipment;
use App\Domains\Webhooks\Models\WebhookEndpoint;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $scale = env('DEMO_SEED_SCALE', app()->environment('testing') ? 'demo' : 'full');
        $isDemo = $scale === 'demo';

        $counts = $isDemo
            ? [
                'categories' => 5,
                'brands' => 3,
                'products' => 25,
                'customers' => 25,
                'orders' => 50,
                'order_items' => 150,
                'coupons' => 5,
                'payments' => 30,
                'shipments' => 20,
                'invoices' => 20,
                'reviews' => 30,
            ]
            : [
                'categories' => 100,
                'brands' => 20,
                'products' => 500,
                'customers' => 5000,
                'orders' => 15000,
                'order_items' => 50000,
                'coupons' => 50,
                'payments' => 10000,
                'shipments' => 8000,
                'invoices' => 8000,
                'reviews' => 5000,
            ];

        $this->command?->info("Seeding with scale={$scale}");

        $admin = User::query()->updateOrCreate(
            ['email' => 'admin@shopdemo.test'],
            [
                'name' => 'ShopDemo Admin',
                'first_name' => 'ShopDemo',
                'last_name' => 'Admin',
                'phone' => '+15550000001',
                'password' => Hash::make('password'),
                'role' => 'admin',
                'status' => 'active',
                'email_verified_at' => now(),
            ]
        );

        Setting::query()->firstOrCreate(
            ['user_id' => $admin->id],
            [
                'enable_notifications' => true,
                'enable_email' => true,
                'enable_push' => true,
                'language' => 'en',
                'timezone' => 'UTC',
                'currency' => 'USD',
            ]
        );

        WebhookEndpoint::query()->updateOrCreate(
            ['name' => 'AMS Integration'],
            [
                'url' => env('AMS_WEBHOOK_URL', 'http://host.docker.internal/api/webhooks/shopdemo'),
                'secret' => env('AMS_WEBHOOK_SECRET', 'shopdemo-ams-secret'),
                'events' => ['support.sms.received', 'support.message.received'],
                'is_active' => true,
            ]
        );

        $password = Hash::make('password');
        $now = now()->toDateTimeString();

        $this->command?->info('Seeding categories...');
        $categoryIds = [];
        foreach (array_chunk(range(1, $counts['categories']), 50) as $chunk) {
            $rows = [];
            foreach ($chunk as $i) {
                $name = "Category {$i}";
                $rows[] = [
                    'uuid' => (string) Str::uuid(),
                    'name' => $name,
                    'slug' => 'category-'.$i.'-'.Str::lower(Str::random(4)),
                    'parent_id' => null,
                    'description' => "Demo category {$i}",
                    'is_active' => true,
                    'sort_order' => $i,
                    'created_at' => $now,
                    'updated_at' => $now,
                ];
            }
            DB::table('categories')->insert($rows);
        }
        $categoryIds = Category::query()->pluck('id')->all();

        $this->command?->info('Seeding brands...');
        foreach (array_chunk(range(1, $counts['brands']), 20) as $chunk) {
            $rows = [];
            foreach ($chunk as $i) {
                $rows[] = [
                    'uuid' => (string) Str::uuid(),
                    'name' => "Brand {$i}",
                    'slug' => 'brand-'.$i.'-'.Str::lower(Str::random(4)),
                    'description' => "Demo brand {$i}",
                    'website' => "https://brand{$i}.example.com",
                    'is_active' => true,
                    'created_at' => $now,
                    'updated_at' => $now,
                ];
            }
            DB::table('brands')->insert($rows);
        }
        $brandIds = Brand::query()->pluck('id')->all();

        $this->command?->info('Seeding products...');
        $productIds = [];
        foreach (array_chunk(range(1, $counts['products']), 100) as $chunk) {
            $rows = [];
            foreach ($chunk as $i) {
                $price = fake()->randomFloat(2, 5, 500);
                $rows[] = [
                    'uuid' => (string) Str::uuid(),
                    'category_id' => $categoryIds[array_rand($categoryIds)],
                    'brand_id' => $brandIds[array_rand($brandIds)],
                    'sku' => 'SKU-'.str_pad((string) $i, 6, '0', STR_PAD_LEFT),
                    'name' => "Product {$i}",
                    'slug' => 'product-'.$i.'-'.Str::lower(Str::random(4)),
                    'description' => "Demo product {$i}",
                    'price' => $price,
                    'compare_at_price' => $price + 10,
                    'cost' => round($price * 0.6, 2),
                    'currency' => 'USD',
                    'is_active' => true,
                    'extra_attributes' => json_encode(['color' => 'black', 'size' => 'M']),
                    'created_at' => $now,
                    'updated_at' => $now,
                ];
            }
            DB::table('products')->insert($rows);
        }
        $productIds = Product::query()->pluck('id')->all();

        $this->command?->info('Seeding inventory for all products...');
        foreach (array_chunk($productIds, 200) as $chunk) {
            $rows = [];
            foreach ($chunk as $productId) {
                $qty = fake()->numberBetween(0, 200);
                $rows[] = [
                    'uuid' => (string) Str::uuid(),
                    'product_id' => $productId,
                    'quantity' => $qty,
                    'reserved_quantity' => 0,
                    'warehouse' => 'main',
                    'low_stock_threshold' => 5,
                    'status' => $qty <= 0 ? 'out_of_stock' : ($qty <= 5 ? 'low_stock' : 'in_stock'),
                    'created_at' => $now,
                    'updated_at' => $now,
                ];
            }
            DB::table('inventory_items')->insert($rows);
        }

        $this->command?->info('Seeding customers with users...');
        $customerIds = [];
        foreach (array_chunk(range(1, $counts['customers']), 100) as $chunk) {
            $userRows = [];
            $customerRows = [];
            foreach ($chunk as $i) {
                $uuid = (string) Str::uuid();
                $first = fake()->firstName();
                $last = fake()->lastName();
                $userRows[] = [
                    'uuid' => $uuid,
                    'name' => "{$first} {$last}",
                    'first_name' => $first,
                    'last_name' => $last,
                    'email' => "customer{$i}_".Str::lower(Str::random(6)).'@shopdemo.test',
                    'phone' => '+1555'.str_pad((string) $i, 7, '0', STR_PAD_LEFT),
                    'password' => $password,
                    'role' => 'customer',
                    'status' => 'active',
                    'email_verified_at' => $now,
                    'created_at' => $now,
                    'updated_at' => $now,
                ];
            }
            DB::table('users')->insert($userRows);

            $insertedUsers = User::query()
                ->whereIn('email', array_column($userRows, 'email'))
                ->pluck('id', 'email');

            foreach ($userRows as $idx => $userRow) {
                $userId = $insertedUsers[$userRow['email']];
                $customerRows[] = [
                    'uuid' => (string) Str::uuid(),
                    'user_id' => $userId,
                    'customer_number' => 'CUS-'.str_pad((string) ($userId), 8, '0', STR_PAD_LEFT),
                    'company_name' => fake()->optional(0.3)->company(),
                    'billing_address' => fake()->streetAddress(),
                    'shipping_address' => fake()->streetAddress(),
                    'city' => fake()->city(),
                    'state' => fake()->state(),
                    'country' => fake()->countryCode(),
                    'postal_code' => fake()->postcode(),
                    'status' => 'active',
                    'created_at' => $now,
                    'updated_at' => $now,
                ];
            }
            DB::table('customers')->insert($customerRows);
        }
        $customerIds = Customer::query()->pluck('id')->all();

        $this->command?->info('Seeding coupons...');
        foreach (range(1, $counts['coupons']) as $i) {
            Coupon::query()->create([
                'code' => 'SAVE'.$i.strtoupper(Str::random(3)),
                'type' => $i % 2 === 0 ? 'percent' : 'fixed',
                'value' => $i % 2 === 0 ? 10 : 15,
                'min_order_amount' => 50,
                'max_uses' => 1000,
                'used_count' => 0,
                'starts_at' => now()->subDays(7),
                'ends_at' => now()->addMonths(3),
                'is_active' => true,
            ]);
        }
        $couponIds = Coupon::query()->pluck('id')->all();

        $this->command?->info('Seeding orders...');
        $orderIds = [];
        $statuses = ['pending', 'paid', 'processing', 'shipped', 'delivered', 'cancelled'];
        foreach (array_chunk(range(1, $counts['orders']), 200) as $chunk) {
            $rows = [];
            foreach ($chunk as $i) {
                $subtotal = fake()->randomFloat(2, 20, 800);
                $discount = fake()->randomFloat(2, 0, 20);
                $tax = round($subtotal * 0.08, 2);
                $shipping = fake()->randomFloat(2, 0, 25);
                $rows[] = [
                    'uuid' => (string) Str::uuid(),
                    'customer_id' => $customerIds[array_rand($customerIds)],
                    'order_number' => 'ORD-'.str_pad((string) $i, 8, '0', STR_PAD_LEFT),
                    'status' => $statuses[array_rand($statuses)],
                    'subtotal' => $subtotal,
                    'discount' => $discount,
                    'tax' => $tax,
                    'shipping_amount' => $shipping,
                    'total' => max(0, $subtotal - $discount + $tax + $shipping),
                    'currency' => 'USD',
                    'coupon_id' => fake()->boolean(20) ? $couponIds[array_rand($couponIds)] : null,
                    'notes' => null,
                    'placed_at' => fake()->dateTimeBetween('-90 days', 'now')->format('Y-m-d H:i:s'),
                    'created_at' => $now,
                    'updated_at' => $now,
                ];
            }
            DB::table('orders')->insert($rows);
        }
        $orderIds = Order::query()->pluck('id')->all();
        $productSkuMap = Product::query()->pluck('sku', 'id')->all();
        $productNameMap = Product::query()->pluck('name', 'id')->all();
        $productPriceMap = Product::query()->pluck('price', 'id')->all();

        $this->seedChunked('order items', 'order_items', $counts['order_items'], 500, function () use ($orderIds, $productIds, $productSkuMap, $productNameMap, $productPriceMap, $now) {
            $productId = $productIds[array_rand($productIds)];
            $qty = random_int(1, 4);
            $unit = (float) $productPriceMap[$productId];

            return [
                'uuid' => (string) Str::uuid(),
                'order_id' => $orderIds[array_rand($orderIds)],
                'product_id' => $productId,
                'sku' => $productSkuMap[$productId],
                'name' => $productNameMap[$productId],
                'quantity' => $qty,
                'unit_price' => $unit,
                'total' => round($unit * $qty, 2),
                'created_at' => $now,
                'updated_at' => $now,
            ];
        });

        $this->seedChunked('payments', 'payments', $counts['payments'], 300, function () use ($orderIds, $now) {
            $status = fake()->randomElement(['pending', 'completed', 'failed', 'refunded']);

            return [
                'uuid' => (string) Str::uuid(),
                'order_id' => $orderIds[array_rand($orderIds)],
                'payment_number' => 'PAY-'.strtoupper(Str::random(10)),
                'method' => fake()->randomElement(['card', 'paypal', 'bank_transfer', 'cod']),
                'amount' => fake()->randomFloat(2, 10, 1000),
                'currency' => 'USD',
                'status' => $status,
                'provider_reference' => strtoupper(Str::random(12)),
                'paid_at' => $status === 'completed' ? $now : null,
                'created_at' => $now,
                'updated_at' => $now,
            ];
        });

        $this->seedChunked('shipments', 'shipments', $counts['shipments'], 300, function () use ($orderIds, $now) {
            return [
                'uuid' => (string) Str::uuid(),
                'order_id' => $orderIds[array_rand($orderIds)],
                'carrier' => fake()->randomElement(['UPS', 'FedEx', 'DHL', 'USPS']),
                'tracking_number' => strtoupper(Str::random(12)),
                'status' => fake()->randomElement(['pending', 'shipped', 'in_transit', 'delivered']),
                'shipped_at' => $now,
                'delivered_at' => null,
                'address' => json_encode([
                    'line1' => fake()->streetAddress(),
                    'city' => fake()->city(),
                    'country' => 'US',
                ]),
                'created_at' => $now,
                'updated_at' => $now,
            ];
        });

        $this->seedChunked('invoices', 'invoices', $counts['invoices'], 300, function () use ($orderIds, $now) {
            $subtotal = fake()->randomFloat(2, 20, 800);
            $tax = round($subtotal * 0.08, 2);

            return [
                'uuid' => (string) Str::uuid(),
                'order_id' => $orderIds[array_rand($orderIds)],
                'invoice_number' => 'INV-'.strtoupper(Str::random(10)),
                'status' => fake()->randomElement(['draft', 'issued', 'paid', 'void']),
                'subtotal' => $subtotal,
                'tax' => $tax,
                'total' => $subtotal + $tax,
                'issued_at' => $now,
                'due_at' => now()->addDays(30)->toDateTimeString(),
                'pdf_path' => null,
                'created_at' => $now,
                'updated_at' => $now,
            ];
        });

        $this->seedChunked('reviews', 'reviews', $counts['reviews'], 300, function () use ($orderIds, $productIds, $customerIds, $now) {
            return [
                'uuid' => (string) Str::uuid(),
                'product_id' => $productIds[array_rand($productIds)],
                'customer_id' => $customerIds[array_rand($customerIds)],
                'rating' => random_int(1, 5),
                'title' => fake()->sentence(4),
                'body' => fake()->paragraph(),
                'is_approved' => true,
                'reviewed_at' => $now,
                'created_at' => $now,
                'updated_at' => $now,
            ];
        });

        $this->command?->info('Seeding complete.');
    }

    private function seedChunked(string $label, string $table, int $total, int $chunkSize, callable $rowFactory): void
    {
        $this->command?->info("Seeding {$label} ({$total})...");
        $buffer = [];
        for ($i = 0; $i < $total; $i++) {
            $buffer[] = $rowFactory();
            if (count($buffer) >= $chunkSize) {
                DB::table($table)->insert($buffer);
                $buffer = [];
            }
        }
        if ($buffer) {
            DB::table($table)->insert($buffer);
        }
    }
}
