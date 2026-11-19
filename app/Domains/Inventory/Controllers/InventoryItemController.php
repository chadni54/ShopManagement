<?php

namespace App\Domains\Inventory\Controllers;

use App\Domains\Inventory\Models\InventoryItem;
use App\Domains\Inventory\Requests\StoreInventoryItemRequest;
use App\Domains\Inventory\Requests\UpdateInventoryItemRequest;
use App\Domains\Inventory\Resources\InventoryItemResource;
use App\Domains\Inventory\Services\InventoryItemService;
use App\Http\Controllers\Controller;
use App\Shared\Http\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class InventoryItemController extends Controller
{
    public function __construct(protected InventoryItemService $service) {}

    public function index(Request $request): JsonResponse
    {
        $paginator = $this->service->list($request);

        return ApiResponse::success(
            InventoryItemResource::collection($paginator),
            'InventoryItem list retrieved'
        );
    }

    public function store(StoreInventoryItemRequest $request): JsonResponse
    {
        $model = $this->service->create($request->validated());

        return ApiResponse::created(
            new InventoryItemResource($model),
            'InventoryItem created successfully'
        );
    }

    public function show(InventoryItem $inventory): JsonResponse
    {
        $model = $this->service->show($inventory);

        return ApiResponse::success(
            new InventoryItemResource($model),
            'InventoryItem retrieved'
        );
    }

    public function update(UpdateInventoryItemRequest $request, InventoryItem $inventory): JsonResponse
    {
        $model = $this->service->update($inventory, $request->validated());

        return ApiResponse::success(
            new InventoryItemResource($model),
            'InventoryItem updated successfully'
        );
    }

    public function destroy(InventoryItem $inventory): JsonResponse
    {
        $this->service->delete($inventory);

        return ApiResponse::noContent('InventoryItem deleted successfully');
    }
}