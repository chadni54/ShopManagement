<?php

namespace App\Domains\Products\Controllers;

use App\Domains\Products\Models\Product;
use App\Domains\Products\Requests\StoreProductRequest;
use App\Domains\Products\Requests\UpdateProductRequest;
use App\Domains\Products\Resources\ProductResource;
use App\Domains\Products\Services\ProductService;
use App\Http\Controllers\Controller;
use App\Shared\Http\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function __construct(protected ProductService $service) {}

    public function index(Request $request): JsonResponse
    {
        $paginator = $this->service->list($request);

        return ApiResponse::success(
            ProductResource::collection($paginator),
            'Product list retrieved'
        );
    }

    public function store(StoreProductRequest $request): JsonResponse
    {
        $model = $this->service->create($request->validated());

        return ApiResponse::created(
            new ProductResource($model),
            'Product created successfully'
        );
    }

    public function show(Product $product): JsonResponse
    {
        $model = $this->service->show($product);

        return ApiResponse::success(
            new ProductResource($model),
            'Product retrieved'
        );
    }

    public function update(UpdateProductRequest $request, Product $product): JsonResponse
    {
        $model = $this->service->update($product, $request->validated());

        return ApiResponse::success(
            new ProductResource($model),
            'Product updated successfully'
        );
    }

    public function destroy(Product $product): JsonResponse
    {
        $this->service->delete($product);

        return ApiResponse::noContent('Product deleted successfully');
    }
}