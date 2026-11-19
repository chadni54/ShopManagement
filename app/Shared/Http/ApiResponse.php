<?php

namespace App\Shared\Http;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Pagination\AbstractPaginator;

class ApiResponse
{
    public static function success(
        mixed $data = null,
        string $message = 'Success',
        int $status = 200
    ): JsonResponse {
        $payload = [
            'success' => true,
            'message' => $message,
            'data' => $data instanceof JsonResource || $data instanceof ResourceCollection
                ? $data->resolve()
                : $data,
        ];

        if ($data instanceof ResourceCollection && $data->resource instanceof AbstractPaginator) {
            $paginator = $data->resource;
            $payload['meta'] = [
                'current_page' => $paginator->currentPage(),
                'per_page' => $paginator->perPage(),
                'total' => $paginator->total(),
                'last_page' => $paginator->lastPage(),
            ];
            $payload['links'] = [
                'first' => $paginator->url(1),
                'last' => $paginator->url($paginator->lastPage()),
                'prev' => $paginator->previousPageUrl(),
                'next' => $paginator->nextPageUrl(),
            ];
        }

        return response()->json($payload, $status);
    }

    public static function error(
        string $message = 'Unexpected Error',
        int $status = 500,
        mixed $errors = null
    ): JsonResponse {
        $payload = [
            'success' => false,
            'message' => $message,
        ];

        if ($errors !== null) {
            $payload['errors'] = $errors;
        }

        return response()->json($payload, $status);
    }

    public static function created(mixed $data = null, string $message = 'Created successfully'): JsonResponse
    {
        return self::success($data, $message, 201);
    }

    public static function noContent(string $message = 'Deleted successfully'): JsonResponse
    {
        return self::success(null, $message, 200);
    }
}
