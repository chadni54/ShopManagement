<?php

namespace App\Domains\Categories\Services;

use App\Domains\Categories\Models\Category;
use App\Domains\Categories\Repositories\CategoryRepository;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\Request;

class CategoryService
{
    public function __construct(
        protected CategoryRepository $repository
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
  1 => 'parent_id',
),
            array (
  0 => 'created_at',
  1 => 'name',
  2 => 'sort_order',
)
        );

        $with = array (
);
        if (! empty($with)) {
            $paginator->getCollection()->load($with);
        }

        return $paginator;
    }

    public function show(Category $model): Category
    {
        $with = array (
);
        if (! empty($with)) {
            $model->load($with);
        }

        return $model;
    }

    public function create(array $data): Category
    {
        /** @var Category $model */
        $model = $this->repository->create($data);

        return $model;
    }

    public function update(Category $model, array $data): Category
    {
        /** @var Category $model */
        $model = $this->repository->update($model, $data);

        return $model;
    }

    public function delete(Category $model): bool
    {
        return $this->repository->delete($model);
    }
}