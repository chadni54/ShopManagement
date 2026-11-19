<?php

namespace App\Domains\Settings\Controllers;

use App\Domains\Settings\Requests\UpdateSettingRequest;
use App\Domains\Settings\Resources\SettingResource;
use App\Domains\Settings\Services\SettingService;
use App\Http\Controllers\Controller;
use App\Shared\Http\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    public function __construct(protected SettingService $service) {}

    public function show(Request $request): JsonResponse
    {
        $setting = $this->service->show($request->user());

        return ApiResponse::success(
            new SettingResource($setting),
            'Settings retrieved'
        );
    }

    public function update(UpdateSettingRequest $request): JsonResponse
    {
        $setting = $this->service->update($request->user(), $request->validated());

        return ApiResponse::success(
            new SettingResource($setting),
            'Settings updated successfully'
        );
    }
}
