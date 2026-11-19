<?php

namespace App\Domains\Orders\Controllers;

use App\Domains\Orders\Models\Order;
use App\Domains\Orders\Requests\CancelOrderRequest;
use App\Domains\Orders\Requests\StoreOrderRequest;
use App\Domains\Orders\Requests\UpdateOrderRequest;
use App\Domains\Orders\Resources\OrderResource;
use App\Domains\Orders\Services\OrderService;
use App\Http\Controllers\Controller;
use App\Shared\Http\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function __construct(protected OrderService $service) {}

    public function index(Request $request): JsonResponse
    {
        $paginator = $this->service->list($request);

        return ApiResponse::success(
            OrderResource::collection($paginator),
            'Order list retrieved'
        );
    }

    public function store(StoreOrderRequest $request): JsonResponse
    {
        $model = $this->service->create($request->validated());

        return ApiResponse::created(
            new OrderResource($model),
            'Order created successfully'
        );
    }

    public function show(Order $order): JsonResponse
    {
        $model = $this->service->show($order);

        return ApiResponse::success(
            new OrderResource($model),
            'Order retrieved'
        );
    }

    public function update(UpdateOrderRequest $request, Order $order): JsonResponse
    {
        $model = $this->service->update($order, $request->validated());

        return ApiResponse::success(
            new OrderResource($model),
            'Order updated successfully'
        );
    }

    public function destroy(Order $order): JsonResponse
    {
        $this->service->delete($order);

        return ApiResponse::noContent('Order deleted successfully');
    }

    public function cancel(CancelOrderRequest $request, Order $order): JsonResponse
    {
        $model = $this->service->cancel($order, $request->validated('reason'));

        return ApiResponse::success(
            new OrderResource($model),
            'Order cancelled successfully'
        );
    }
}
