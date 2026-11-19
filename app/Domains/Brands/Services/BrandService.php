<?php

namespace App\Domains\Brands\Services;

use App\Domains\Brands\Models\Brand;
use App\Domains\Brands\Repositories\BrandRepository;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\Request;

class BrandService
{
    public function __construct(
        protected BrandRepository $repository
    ) {}

    public function list(Request $request): LengthAwarePaginator
    {
        $paginator = $this->repository->paginate(
            $request,
            array (
  0 => 'name',
  1 => 'slug',
  2 => 'description',
),
            array (
  0 => 'is_active',
),
            array (
  0 => 'created_at',
  1 => 'name',
)
        );

        $with = array (
);
        if (! empty($with)) {
            $paginator->getCollection()->load($with);
        }

        return $paginator;
    }

    public function show(Brand $model): Brand
    {
        $with = array (
);
        if (! empty($with)) {
            $model->load($with);
        }

        return $model;
    }

    public function create(array $data): Brand
    {
        /** @var Brand $model */
        $model = $this->repository->create($data);

        return $model;
    }

    public function update(Brand $model, array $data): Brand
    {
        /** @var Brand $model */
        $model = $this->repository->update($model, $data);

        return $model;
    }

    public function delete(Brand $model): bool
    {
        return $this->repository->delete($model);
    }
}