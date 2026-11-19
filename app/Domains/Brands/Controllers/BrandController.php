<?php

namespace App\Domains\Brands\Controllers;

use App\Domains\Brands\Models\Brand;
use App\Domains\Brands\Requests\StoreBrandRequest;
use App\Domains\Brands\Requests\UpdateBrandRequest;
use App\Domains\Brands\Resources\BrandResource;
use App\Domains\Brands\Services\BrandService;
use App\Http\Controllers\Controller;
use App\Shared\Http\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class BrandController extends Controller
{
    public function __construct(protected BrandService $service) {}

    public function index(Request $request): JsonResponse
    {
        $paginator = $this->service->list($request);

        return ApiResponse::success(
            BrandResource::collection($paginator),
            'Brand list retrieved'
        );
    }

    public function store(StoreBrandRequest $request): JsonResponse
    {
        $model = $this->service->create($request->validated());

        return ApiResponse::created(
            new BrandResource($model),
            'Brand created successfully'
        );
    }

    public function show(Brand $brand): JsonResponse
    {
        $model = $this->service->show($brand);

        return ApiResponse::success(
            new BrandResource($model),
            'Brand retrieved'
        );
    }

    public function update(UpdateBrandRequest $request, Brand $brand): JsonResponse
    {
        $model = $this->service->update($brand, $request->validated());

        return ApiResponse::success(
            new BrandResource($model),
            'Brand updated successfully'
        );
    }

    public function destroy(Brand $brand): JsonResponse
    {
        $this->service->delete($brand);

        return ApiResponse::noContent('Brand deleted successfully');
    }
}