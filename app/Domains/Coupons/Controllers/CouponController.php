<?php

namespace App\Domains\Coupons\Controllers;

use App\Domains\Coupons\Models\Coupon;
use App\Domains\Coupons\Requests\StoreCouponRequest;
use App\Domains\Coupons\Requests\UpdateCouponRequest;
use App\Domains\Coupons\Resources\CouponResource;
use App\Domains\Coupons\Services\CouponService;
use App\Http\Controllers\Controller;
use App\Shared\Http\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CouponController extends Controller
{
    public function __construct(protected CouponService $service) {}

    public function index(Request $request): JsonResponse
    {
        $paginator = $this->service->list($request);

        return ApiResponse::success(
            CouponResource::collection($paginator),
            'Coupon list retrieved'
        );
    }

    public function store(StoreCouponRequest $request): JsonResponse
    {
        $model = $this->service->create($request->validated());

        return ApiResponse::created(
            new CouponResource($model),
            'Coupon created successfully'
        );
    }

    public function show(Coupon $coupon): JsonResponse
    {
        $model = $this->service->show($coupon);

        return ApiResponse::success(
            new CouponResource($model),
            'Coupon retrieved'
        );
    }

    public function update(UpdateCouponRequest $request, Coupon $coupon): JsonResponse
    {
        $model = $this->service->update($coupon, $request->validated());

        return ApiResponse::success(
            new CouponResource($model),
            'Coupon updated successfully'
        );
    }

    public function destroy(Coupon $coupon): JsonResponse
    {
        $this->service->delete($coupon);

        return ApiResponse::noContent('Coupon deleted successfully');
    }
}