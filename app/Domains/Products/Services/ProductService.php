<?php

namespace App\Domains\Products\Services;

use App\Domains\Products\Models\Product;
use App\Domains\Products\Repositories\ProductRepository;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\Request;

class ProductService
{
    public function __construct(
        protected ProductRepository $repository
    ) {}

    public function list(Request $request): LengthAwarePaginator
    {
        $paginator = $this->repository->paginate(
            $request,
            array (
  0 => 'name',
  1 => 'sku',
  2 => 'slug',
  3 => 'description',
),
            array (
  0 => 'category_id',
  1 => 'brand_id',
  2 => 'is_active',
  3 => 'currency',
),
            array (
  0 => 'created_at',
  1 => 'name',
  2 => 'price',
  3 => 'sku',
)
        );

        $with = array (
  0 => 'category',
  1 => 'brand',
);
        if (! empty($with)) {
            $paginator->getCollection()->load($with);
        }

        return $paginator;
    }

    public function show(Product $model): Product
    {
        $with = array (
  0 => 'category',
  1 => 'brand',
);
        if (! empty($with)) {
            $model->load($with);
        }

        return $model;
    }

    public function create(array $data): Product
    {
        $data = $this->mapAttributes($data);

        /** @var Product $model */
        $model = $this->repository->create($data);

        return $model;
    }

    public function update(Product $model, array $data): Product
    {
        $data = $this->mapAttributes($data);

        /** @var Product $model */
        $model = $this->repository->update($model, $data);

        return $model;
    }

    /**
     * @param  array<string, mixed>  $data
     * @return array<string, mixed>
     */
    private function mapAttributes(array $data): array
    {
        if (array_key_exists('attributes', $data)) {
            $data['extra_attributes'] = $data['attributes'];
            unset($data['attributes']);
        }

        return $data;
    }

    public function delete(Product $model): bool
    {
        return $this->repository->delete($model);
    }
}