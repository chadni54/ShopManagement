<?php

namespace App\Domains\Inventory\Services;

use App\Domains\Inventory\Models\InventoryItem;
use App\Domains\Inventory\Repositories\InventoryItemRepository;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\Request;

class InventoryItemService
{
    public function __construct(
        protected InventoryItemRepository $repository
    ) {}

    public function list(Request $request): LengthAwarePaginator
    {
        $paginator = $this->repository->paginate(
            $request,
            array (
  0 => 'warehouse',
  1 => 'status',
),
            array (
  0 => 'product_id',
  1 => 'warehouse',
  2 => 'status',
),
            array (
  0 => 'created_at',
  1 => 'quantity',
  2 => 'warehouse',
)
        );

        $with = array (
  0 => 'product',
);
        if (! empty($with)) {
            $paginator->getCollection()->load($with);
        }

        return $paginator;
    }

    public function show(InventoryItem $model): InventoryItem
    {
        $with = array (
  0 => 'product',
);
        if (! empty($with)) {
            $model->load($with);
        }

        return $model;
    }

    public function create(array $data): InventoryItem
    {
        $qty = (int) ($data['quantity'] ?? 0);
        $threshold = (int) ($data['low_stock_threshold'] ?? 5);
        if (empty($data['status'])) {
            $data['status'] = $qty <= 0 ? 'out_of_stock' : ($qty <= $threshold ? 'low_stock' : 'in_stock');
        }
        /** @var InventoryItem $model */
        $model = $this->repository->create($data);

        return $model;
    }

    public function update(InventoryItem $model, array $data): InventoryItem
    {
        /** @var InventoryItem $model */
        $model = $this->repository->update($model, $data);

        $qty = (int) $model->quantity;
        $threshold = (int) $model->low_stock_threshold;
        if (! array_key_exists('status', $data)) {
            $model = $this->repository->update($model, [
                'status' => $qty <= 0 ? 'out_of_stock' : ($qty <= $threshold ? 'low_stock' : 'in_stock'),
            ]);
        }

        event(new \App\Events\InventoryUpdated($model->fresh()->load('product')->toArray()));
        return $model;
    }

    public function delete(InventoryItem $model): bool
    {
        return $this->repository->delete($model);
    }
}