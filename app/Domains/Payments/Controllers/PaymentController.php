<?php

namespace App\Domains\Payments\Controllers;

use App\Domains\Payments\Models\Payment;
use App\Domains\Payments\Requests\StorePaymentRequest;
use App\Domains\Payments\Requests\UpdatePaymentRequest;
use App\Domains\Payments\Resources\PaymentResource;
use App\Domains\Payments\Services\PaymentService;
use App\Http\Controllers\Controller;
use App\Shared\Http\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function __construct(protected PaymentService $service) {}

    public function index(Request $request): JsonResponse
    {
        $paginator = $this->service->list($request);

        return ApiResponse::success(
            PaymentResource::collection($paginator),
            'Payment list retrieved'
        );
    }

    public function store(StorePaymentRequest $request): JsonResponse
    {
        $model = $this->service->create($request->validated());

        return ApiResponse::created(
            new PaymentResource($model),
            'Payment created successfully'
        );
    }

    public function show(Payment $payment): JsonResponse
    {
        $model = $this->service->show($payment);

        return ApiResponse::success(
            new PaymentResource($model),
            'Payment retrieved'
        );
    }

    public function update(UpdatePaymentRequest $request, Payment $payment): JsonResponse
    {
        $model = $this->service->update($payment, $request->validated());

        return ApiResponse::success(
            new PaymentResource($model),
            'Payment updated successfully'
        );
    }

    public function destroy(Payment $payment): JsonResponse
    {
        $this->service->delete($payment);

        return ApiResponse::noContent('Payment deleted successfully');
    }
}