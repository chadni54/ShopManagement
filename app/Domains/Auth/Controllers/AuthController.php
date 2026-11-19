<?php

namespace App\Domains\Auth\Controllers;

use App\Domains\Auth\Requests\LoginRequest;
use App\Domains\Auth\Requests\UpdateProfileRequest;
use App\Domains\Auth\Services\AuthService;
use App\Domains\Users\Resources\UserResource;
use App\Http\Controllers\Controller;
use App\Shared\Http\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use OpenApi\Attributes as OA;

#[OA\Tag(name: 'Auth', description: 'Authentication endpoints')]
class AuthController extends Controller
{
    public function __construct(protected AuthService $service) {}

    #[OA\Post(
        path: '/api/v1/login',
        tags: ['Auth'],
        summary: 'Login and issue Sanctum token',
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ['email', 'password'],
                properties: [
                    new OA\Property(property: 'email', type: 'string', format: 'email', example: 'admin@shopdemo.test'),
                    new OA\Property(property: 'password', type: 'string', format: 'password', example: 'password'),
                    new OA\Property(property: 'device_name', type: 'string', example: 'postman'),
                ]
            )
        ),
        responses: [
            new OA\Response(response: 200, description: 'Authenticated'),
            new OA\Response(response: 422, description: 'Validation failed'),
        ]
    )]
    public function login(LoginRequest $request): JsonResponse
    {
        $result = $this->service->login(
            $request->string('email')->toString(),
            $request->string('password')->toString(),
            $request->string('device_name')->toString() ?: 'api'
        );

        return ApiResponse::success([
            'token' => $result['token'],
            'token_type' => 'Bearer',
            'user' => (new UserResource($result['user']))->resolve(),
        ], 'Login successful');
    }

    #[OA\Post(
        path: '/api/v1/logout',
        tags: ['Auth'],
        summary: 'Revoke current access token',
        security: [['bearerAuth' => []]],
        responses: [
            new OA\Response(response: 200, description: 'Logged out'),
        ]
    )]
    public function logout(Request $request): JsonResponse
    {
        $this->service->logout($request->user());

        return ApiResponse::success(null, 'Logged out successfully');
    }

    #[OA\Get(
        path: '/api/v1/profile',
        tags: ['Auth'],
        summary: 'Get authenticated user profile',
        security: [['bearerAuth' => []]],
        responses: [
            new OA\Response(response: 200, description: 'Profile'),
        ]
    )]
    public function profile(Request $request): JsonResponse
    {
        return ApiResponse::success(
            new UserResource($request->user()),
            'Profile retrieved'
        );
    }

    #[OA\Put(
        path: '/api/v1/profile',
        tags: ['Auth'],
        summary: 'Update authenticated user profile',
        security: [['bearerAuth' => []]],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(ref: '#/components/schemas/UpdateProfile')
        ),
        responses: [
            new OA\Response(response: 200, description: 'Updated'),
            new OA\Response(response: 401, description: 'Unauthenticated'),
            new OA\Response(response: 422, description: 'Validation failed'),
        ]
    )]
    public function updateProfile(UpdateProfileRequest $request): JsonResponse
    {
        $user = $this->service->updateProfile($request->user(), $request->validated());

        return ApiResponse::success(
            new UserResource($user),
            'Profile updated successfully'
        );
    }
}
