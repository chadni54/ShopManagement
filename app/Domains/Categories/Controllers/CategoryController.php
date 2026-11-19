<?php

namespace App\Domains\Categories\Controllers;

use App\Domains\Categories\Models\Category;
use App\Domains\Categories\Requests\StoreCategoryRequest;
use App\Domains\Categories\Requests\UpdateCategoryRequest;
use App\Domains\Categories\Resources\CategoryResource;
use App\Domains\Categories\Services\CategoryService;
use App\Http\Controllers\Controller;
use App\Shared\Http\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function __construct(protected CategoryService $service) {}

    public function index(Request $request): JsonResponse
    {
        $paginator = $this->service->list($request);

        return ApiResponse::success(
            CategoryResource::collection($paginator),
            'Category list retrieved'
        );
    }

    public function store(StoreCategoryRequest $request): JsonResponse
    {
        $model = $this->service->create($request->validated());

        return ApiResponse::created(
            new CategoryResource($model),
            'Category created successfully'
        );
    }

    public function show(Category $category): JsonResponse
    {
        $model = $this->service->show($category);

        return ApiResponse::success(
            new CategoryResource($model),
            'Category retrieved'
        );
    }

    public function update(UpdateCategoryRequest $request, Category $category): JsonResponse
    {
        $model = $this->service->update($category, $request->validated());

        return ApiResponse::success(
            new CategoryResource($model),
            'Category updated successfully'
        );
    }

    public function destroy(Category $category): JsonResponse
    {
        $this->service->delete($category);

        return ApiResponse::noContent('Category deleted successfully');
    }
}