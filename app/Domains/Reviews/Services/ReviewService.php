<?php

namespace App\Domains\Reviews\Services;

use App\Domains\Reviews\Models\Review;
use App\Domains\Reviews\Repositories\ReviewRepository;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\Request;

class ReviewService
{
    public function __construct(
        protected ReviewRepository $repository
    ) {}

    public function list(Request $request): LengthAwarePaginator
    {
        $paginator = $this->repository->paginate(
            $request,
            array (
  0 => 'title',
  1 => 'body',
),
            array (
  0 => 'product_id',
  1 => 'customer_id',
  2 => 'is_approved',
  3 => 'rating',
),
            array (
  0 => 'created_at',
  1 => 'rating',
  2 => 'reviewed_at',
)
        );

        $with = array (
  0 => 'product',
  1 => 'customer',
);
        if (! empty($with)) {
            $paginator->getCollection()->load($with);
        }

        return $paginator;
    }

    public function show(Review $model): Review
    {
        $with = array (
  0 => 'product',
  1 => 'customer',
);
        if (! empty($with)) {
            $model->load($with);
        }

        return $model;
    }

    public function create(array $data): Review
    {
        if (empty($data['reviewed_at'])) {
            $data['reviewed_at'] = now();
        }
        /** @var Review $model */
        $model = $this->repository->create($data);

        return $model;
    }

    public function update(Review $model, array $data): Review
    {
        /** @var Review $model */
        $model = $this->repository->update($model, $data);

        return $model;
    }

    public function delete(Review $model): bool
    {
        return $this->repository->delete($model);
    }
}