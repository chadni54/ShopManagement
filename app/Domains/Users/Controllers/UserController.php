<?php

namespace App\Domains\Users\Controllers;

use App\Domains\Users\Requests\StoreUserRequest;
use App\Domains\Users\Requests\UpdateUserRequest;
use App\Domains\Users\Resources\UserResource;
use App\Domains\Users\Services\UserService;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Shared\Http\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function __construct(protected UserService $service) {}

    public function index(Request $request): JsonResponse
    {
        $paginator = $this->service->list($request);

        return ApiResponse::success(
            UserResource::collection($paginator),
            'User list retrieved'
        );
    }

    public function store(StoreUserRequest $request): JsonResponse
    {
        $model = $this->service->create($request->validated());

        return ApiResponse::created(
            new UserResource($model),
            'User created successfully'
        );
    }

    public function show(User $user): JsonResponse
    {
        $model = $this->service->show($user);

        return ApiResponse::success(
            new UserResource($model),
            'User retrieved'
        );
    }

    public function update(UpdateUserRequest $request, User $user): JsonResponse
    {
        $model = $this->service->update($user, $request->validated());

        return ApiResponse::success(
            new UserResource($model),
            'User updated successfully'
        );
    }

    public function destroy(User $user): JsonResponse
    {
        $this->service->delete($user);

        return ApiResponse::noContent('User deleted successfully');
    }
}
