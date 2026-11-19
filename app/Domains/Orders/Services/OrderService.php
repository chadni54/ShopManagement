<?php

namespace App\Domains\Orders\Services;

use App\Domains\Orders\Models\Order;
use App\Domains\Orders\Models\OrderItem;
use App\Domains\Orders\Repositories\OrderRepository;
use App\Domains\Products\Models\Product;
use App\Events\OrderCancelled;
use App\Events\OrderCreated;
use App\Events\OrderUpdated;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class OrderService
{
    public function __construct(
        protected OrderRepository $repository
    ) {}

    public function list(Request $request): LengthAwarePaginator
    {
        $paginator = $this->repository->paginate(
            $request,
            ['order_number', 'notes', 'status'],
            ['customer_id', 'status', 'currency', 'coupon_id'],
            ['created_at', 'placed_at', 'total', 'order_number']
        );

        $paginator->getCollection()->load(['customer', 'items', 'coupon']);

        return $paginator;
    }

    public function show(Order $order): Order
    {
        $order->load(['customer', 'items.product', 'coupon', 'payments', 'shipments', 'invoice']);

        return $order;
    }

    public function create(array $data): Order
    {
        return DB::transaction(function () use ($data): Order {
            $items = $data['items'] ?? [];
            unset($data['items']);

            if (empty($items)) {
                throw ValidationException::withMessages([
                    'items' => ['At least one order item is required.'],
                ]);
            }

            if (empty($data['order_number'])) {
                $data['order_number'] = 'ORD-'.strtoupper(Str::random(10));
            }

            $data['placed_at'] = $data['placed_at'] ?? now();
            $data['status'] = $data['status'] ?? 'pending';
            $data['currency'] = $data['currency'] ?? 'USD';

            $computed = $this->computeTotals($items, $data);
            $data = array_merge($data, $computed);

            /** @var Order $order */
            $order = $this->repository->create($data);

            foreach ($items as $item) {
                $product = Product::query()->findOrFail($item['product_id']);
                $qty = (int) $item['quantity'];
                $unit = isset($item['unit_price']) ? (float) $item['unit_price'] : (float) $product->price;

                OrderItem::query()->create([
                    'order_id' => $order->id,
                    'product_id' => $product->id,
                    'sku' => $item['sku'] ?? $product->sku,
                    'name' => $item['name'] ?? $product->name,
                    'quantity' => $qty,
                    'unit_price' => $unit,
                    'total' => round($unit * $qty, 2),
                ]);
            }

            $order->load(['customer', 'items', 'coupon']);

            event(new OrderCreated($order->toArray()));

            return $order;
        });
    }

    public function update(Order $order, array $data): Order
    {
        /** @var Order $model */
        $model = $this->repository->update($order, $data);
        $model->load(['customer', 'items', 'coupon']);

        event(new OrderUpdated($model->toArray()));

        return $model;
    }

    public function cancel(Order $order, ?string $reason = null): Order
    {
        if ($order->status === 'cancelled') {
            throw ValidationException::withMessages([
                'status' => ['Order is already cancelled.'],
            ]);
        }

        $notes = $order->notes;
        if ($reason) {
            $notes = trim(($notes ? $notes."\n" : '').'Cancellation: '.$reason);
        }

        /** @var Order $model */
        $model = $this->repository->update($order, [
            'status' => 'cancelled',
            'notes' => $notes,
        ]);

        $model->load(['customer', 'items', 'coupon']);

        event(new OrderCancelled($model->toArray()));

        return $model;
    }

    public function delete(Order $order): bool
    {
        return $this->repository->delete($order);
    }

    /**
     * @param  array<int, array<string, mixed>>  $items
     * @param  array<string, mixed>  $data
     * @return array{subtotal: float, discount: float, tax: float, shipping_amount: float, total: float}
     */
    private function computeTotals(array $items, array $data): array
    {
        $subtotal = 0.0;
        foreach ($items as $item) {
            $product = Product::query()->findOrFail($item['product_id']);
            $qty = (int) $item['quantity'];
            $unit = isset($item['unit_price']) ? (float) $item['unit_price'] : (float) $product->price;
            $subtotal += $unit * $qty;
        }

        $discount = (float) ($data['discount'] ?? 0);
        $tax = (float) ($data['tax'] ?? 0);
        $shipping = (float) ($data['shipping_amount'] ?? 0);
        $total = max(0, $subtotal - $discount + $tax + $shipping);

        return [
            'subtotal' => round($subtotal, 2),
            'discount' => round($discount, 2),
            'tax' => round($tax, 2),
            'shipping_amount' => round($shipping, 2),
            'total' => round($total, 2),
        ];
    }
}
