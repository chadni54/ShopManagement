<?php

namespace App\Domains\Customers\Controllers;

use App\Domains\Customers\Models\Customer;
use App\Domains\Customers\Requests\StoreCustomerRequest;
use App\Domains\Customers\Requests\UpdateCustomerRequest;
use App\Domains\Customers\Resources\CustomerResource;
use App\Domains\Customers\Services\CustomerService;
use App\Http\Controllers\Controller;
use App\Shared\Http\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    public function __construct(protected CustomerService $service) {}

    public function index(Request $request): JsonResponse
    {
        $paginator = $this->service->list($request);

        return ApiResponse::success(
            CustomerResource::collection($paginator),
            'Customer list retrieved'
        );
    }

    public function store(StoreCustomerRequest $request): JsonResponse
    {
        $model = $this->service->create($request->validated());

        return ApiResponse::created(
            new CustomerResource($model),
            'Customer created successfully'
        );
    }

    public function show(Customer $customer): JsonResponse
    {
        $model = $this->service->show($customer);

        return ApiResponse::success(
            new CustomerResource($model),
            'Customer retrieved'
        );
    }

    public function update(UpdateCustomerRequest $request, Customer $customer): JsonResponse
    {
        $model = $this->service->update($customer, $request->validated());

        return ApiResponse::success(
            new CustomerResource($model),
            'Customer updated successfully'
        );
    }

    public function destroy(Customer $customer): JsonResponse
    {
        $this->service->delete($customer);

        return ApiResponse::noContent('Customer deleted successfully');
    }
}
