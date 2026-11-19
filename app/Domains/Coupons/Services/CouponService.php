<?php

namespace App\Domains\Coupons\Services;

use App\Domains\Coupons\Models\Coupon;
use App\Domains\Coupons\Repositories\CouponRepository;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\Request;

class CouponService
{
    public function __construct(
        protected CouponRepository $repository
    ) {}

    public function list(Request $request): LengthAwarePaginator
    {
        $paginator = $this->repository->paginate(
            $request,
            array (
  0 => 'code',
),
            array (
  0 => 'type',
  1 => 'is_active',
),
            array (
  0 => 'created_at',
  1 => 'code',
  2 => 'starts_at',
  3 => 'ends_at',
)
        );

        $with = array (
);
        if (! empty($with)) {
            $paginator->getCollection()->load($with);
        }

        return $paginator;
    }

    public function show(Coupon $model): Coupon
    {
        $with = array (
);
        if (! empty($with)) {
            $model->load($with);
        }

        return $model;
    }

    public function create(array $data): Coupon
    {
        /** @var Coupon $model */
        $model = $this->repository->create($data);

        return $model;
    }

    public function update(Coupon $model, array $data): Coupon
    {
        /** @var Coupon $model */
        $model = $this->repository->update($model, $data);

        return $model;
    }

    public function delete(Coupon $model): bool
    {
        return $this->repository->delete($model);
    }
}