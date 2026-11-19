<?php

namespace App\Domains\Shipping\Controllers;

use App\Domains\Shipping\Models\Shipment;
use App\Domains\Shipping\Requests\StoreShipmentRequest;
use App\Domains\Shipping\Requests\UpdateShipmentRequest;
use App\Domains\Shipping\Resources\ShipmentResource;
use App\Domains\Shipping\Services\ShipmentService;
use App\Http\Controllers\Controller;
use App\Shared\Http\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ShipmentController extends Controller
{
    public function __construct(protected ShipmentService $service) {}

    public function index(Request $request): JsonResponse
    {
        $paginator = $this->service->list($request);

        return ApiResponse::success(
            ShipmentResource::collection($paginator),
            'Shipment list retrieved'
        );
    }

    public function store(StoreShipmentRequest $request): JsonResponse
    {
        $model = $this->service->create($request->validated());

        return ApiResponse::created(
            new ShipmentResource($model),
            'Shipment created successfully'
        );
    }

    public function show(Shipment $shipping): JsonResponse
    {
        $model = $this->service->show($shipping);

        return ApiResponse::success(
            new ShipmentResource($model),
            'Shipment retrieved'
        );
    }

    public function update(UpdateShipmentRequest $request, Shipment $shipping): JsonResponse
    {
        $model = $this->service->update($shipping, $request->validated());

        return ApiResponse::success(
            new ShipmentResource($model),
            'Shipment updated successfully'
        );
    }

    public function destroy(Shipment $shipping): JsonResponse
    {
        $this->service->delete($shipping);

        return ApiResponse::noContent('Shipment deleted successfully');
    }
}