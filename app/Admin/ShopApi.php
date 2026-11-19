<?php

namespace App\Admin;

use Illuminate\Contracts\Http\Kernel;
use Illuminate\Http\Request;
use Illuminate\Routing\Route;
use Illuminate\Routing\Router;
use Illuminate\Support\Facades\Facade;
use ReflectionClass;
use Throwable;

class ShopApi
{
    public function get(string $path, array $query = []): ApiResult
    {
        return $this->send('GET', $path, query: $query);
    }

    public function post(string $path, array $data = []): ApiResult
    {
        return $this->send('POST', $path, $data);
    }

    public function put(string $path, array $data = []): ApiResult
    {
        return $this->send('PUT', $path, $data);
    }

    public function delete(string $path): ApiResult
    {
        return $this->send('DELETE', $path);
    }

    /**
     * Call /api/v1 inside this process so the dashboard uses the real API
     * without a second HTTP hop (which deadlocks on php artisan serve).
     */
    private function send(string $method, string $path, array $data = [], array $query = []): ApiResult
    {
        $uri = '/api/v1/'.ltrim($path, '/');

        if ($query !== []) {
            $uri .= '?'.http_build_query($query);
        }

        $original = request();
        $server = [
            'HTTP_ACCEPT' => 'application/json',
            'CONTENT_TYPE' => 'application/json',
            'HTTP_HOST' => $original->getHttpHost(),
            'SERVER_NAME' => $original->getHost(),
            'SERVER_PORT' => (string) $original->getPort(),
            'HTTPS' => $original->isSecure() ? 'on' : 'off',
            'REMOTE_ADDR' => $original->ip() ?: '127.0.0.1',
        ];

        $token = session('api_token');

        if (is_string($token) && $token !== '') {
            $server['HTTP_AUTHORIZATION'] = 'Bearer '.$token;
        }

        $content = in_array($method, ['POST', 'PUT', 'PATCH', 'DELETE'], true)
            ? json_encode($data, JSON_THROW_ON_ERROR)
            : null;

        $subRequest = Request::create($uri, $method, [], [], [], $server, $content);

        /** @var Kernel $kernel */
        $kernel = app(Kernel::class);
        /** @var Router $router */
        $router = app('router');
        $originalRoute = $router->current();

        try {
            $response = $kernel->handle($subRequest);
        } catch (Throwable $e) {
            $response = response()->json([
                'success' => false,
                'message' => $e->getMessage() ?: 'Unexpected Error',
            ], 500);
        } finally {
            $this->restoreRouter($router, $original, $originalRoute);
            app()->instance('request', $original);
            Facade::clearResolvedInstance('request');
        }

        $decoded = json_decode($response->getContent(), true);

        if (! is_array($decoded)) {
            $decoded = [];
        }

        return new ApiResult(
            successful: $response->isSuccessful() && ($decoded['success'] ?? false) === true,
            status: $response->getStatusCode(),
            data: $decoded['data'] ?? null,
            message: (string) ($decoded['message'] ?? ''),
            errors: is_array($decoded['errors'] ?? null) ? $decoded['errors'] : [],
            meta: is_array($decoded['meta'] ?? null) ? $decoded['meta'] : [],
        );
    }

    private function restoreRouter(Router $router, Request $request, mixed $route): void
    {
        $reflection = new ReflectionClass($router);

        foreach (['currentRequest' => $request, 'current' => $route] as $property => $value) {
            $reflection->getProperty($property)->setValue($router, $value);
        }

        if ($route) {
            app()->instance(Route::class, $route);
        }
    }
}
