<?php

namespace App\Domains\Reviews\Controllers;

use App\Domains\Reviews\Models\Review;
use App\Domains\Reviews\Requests\StoreReviewRequest;
use App\Domains\Reviews\Requests\UpdateReviewRequest;
use App\Domains\Reviews\Resources\ReviewResource;
use App\Domains\Reviews\Services\ReviewService;
use App\Http\Controllers\Controller;
use App\Shared\Http\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    public function __construct(protected ReviewService $service) {}

    public function index(Request $request): JsonResponse
    {
        $paginator = $this->service->list($request);

        return ApiResponse::success(
            ReviewResource::collection($paginator),
            'Review list retrieved'
        );
    }

    public function store(StoreReviewRequest $request): JsonResponse
    {
        $model = $this->service->create($request->validated());

        return ApiResponse::created(
            new ReviewResource($model),
            'Review created successfully'
        );
    }

    public function show(Review $review): JsonResponse
    {
        $model = $this->service->show($review);

        return ApiResponse::success(
            new ReviewResource($model),
            'Review retrieved'
        );
    }

    public function update(UpdateReviewRequest $request, Review $review): JsonResponse
    {
        $model = $this->service->update($review, $request->validated());

        return ApiResponse::success(
            new ReviewResource($model),
            'Review updated successfully'
        );
    }

    public function destroy(Review $review): JsonResponse
    {
        $this->service->delete($review);

        return ApiResponse::noContent('Review deleted successfully');
    }
}