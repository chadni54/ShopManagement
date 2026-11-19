<?php

namespace App\Domains\Shipping\Services;

use App\Domains\Shipping\Models\Shipment;
use App\Domains\Shipping\Repositories\ShipmentRepository;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\Request;

class ShipmentService
{
    public function __construct(
        protected ShipmentRepository $repository
    ) {}

    public function list(Request $request): LengthAwarePaginator
    {
        $paginator = $this->repository->paginate(
            $request,
            array (
  0 => 'carrier',
  1 => 'tracking_number',
  2 => 'status',
),
            array (
  0 => 'order_id',
  1 => 'status',
  2 => 'carrier',
),
            array (
  0 => 'created_at',
  1 => 'shipped_at',
  2 => 'delivered_at',
)
        );

        $with = array (
  0 => 'order',
);
        if (! empty($with)) {
            $paginator->getCollection()->load($with);
        }

        return $paginator;
    }

    public function show(Shipment $model): Shipment
    {
        $with = array (
  0 => 'order',
);
        if (! empty($with)) {
            $model->load($with);
        }

        return $model;
    }

    public function create(array $data): Shipment
    {
        /** @var Shipment $model */
        $model = $this->repository->create($data);

        return $model;
    }

    public function update(Shipment $model, array $data): Shipment
    {
        /** @var Shipment $model */
        $model = $this->repository->update($model, $data);

        return $model;
    }

    public function delete(Shipment $model): bool
    {
        return $this->repository->delete($model);
    }
}