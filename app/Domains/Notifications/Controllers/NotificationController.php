<?php

namespace App\Domains\Notifications\Controllers;

use App\Domains\Notifications\Models\AppNotification;
use App\Domains\Notifications\Resources\AppNotificationResource;
use App\Domains\Notifications\Services\NotificationService;
use App\Http\Controllers\Controller;
use App\Shared\Http\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function __construct(protected NotificationService $service) {}

    public function index(Request $request): JsonResponse
    {
        $paginator = $this->service->list($request->user(), $request);

        return ApiResponse::success(
            AppNotificationResource::collection($paginator),
            'Notifications retrieved'
        );
    }

    public function markRead(Request $request, AppNotification $notification): JsonResponse
    {
        $model = $this->service->markRead($request->user(), $notification);

        return ApiResponse::success(
            new AppNotificationResource($model),
            'Notification marked as read'
        );
    }

    public function markAllRead(Request $request): JsonResponse
    {
        $count = $this->service->markAllRead($request->user());

        return ApiResponse::success(
            ['updated' => $count],
            'All notifications marked as read'
        );
    }
}
