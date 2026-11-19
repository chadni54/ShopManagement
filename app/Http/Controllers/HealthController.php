<?php

namespace App\Http\Controllers;

use App\Shared\Http\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use OpenApi\Attributes as OA;
use Throwable;

class HealthController extends Controller
{
    #[OA\Get(
        path: '/health',
        tags: ['Health'],
        summary: 'Health check',
        responses: [
            new OA\Response(response: 200, description: 'Service healthy'),
            new OA\Response(response: 503, description: 'Service degraded'),
        ]
    )]
    #[OA\Get(
        path: '/api/v1/health',
        tags: ['Health'],
        summary: 'Versioned health check',
        responses: [
            new OA\Response(response: 200, description: 'Service healthy'),
            new OA\Response(response: 503, description: 'Service degraded'),
        ]
    )]
    public function __invoke(): JsonResponse
    {
        $database = 'ok';

        try {
            DB::connection()->getPdo();
            DB::select('select 1');
        } catch (Throwable) {
            $database = 'error';
        }

        $payload = [
            'status' => $database === 'ok' ? 'ok' : 'degraded',
            'app' => config('app.name'),
            'version' => config('app.version', '1.0.0'),
            'timestamp' => now()->toIso8601String(),
            'database' => $database,
        ];

        return ApiResponse::success(
            $payload,
            $database === 'ok' ? 'Healthy' : 'Degraded',
            $database === 'ok' ? 200 : 503
        );
    }
}
