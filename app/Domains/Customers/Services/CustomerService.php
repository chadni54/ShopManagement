<?php

namespace App\Domains\Customers\Services;

use App\Domains\Customers\Models\Customer;
use App\Domains\Customers\Repositories\CustomerRepository;
use App\Events\CustomerCreated;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CustomerService
{
    public function __construct(
        protected CustomerRepository $repository
    ) {}

    public function list(Request $request): LengthAwarePaginator
    {
        $paginator = $this->repository->paginate(
            $request,
            ['customer_number', 'company_name', 'city', 'country'],
            ['status', 'user_id', 'country'],
            ['created_at', 'customer_number', 'company_name']
        );

        $paginator->getCollection()->load(['user']);

        return $paginator;
    }

    public function show(Customer $customer): Customer
    {
        $customer->load(['user']);

        return $customer;
    }

    public function create(array $data): Customer
    {
        if (empty($data['customer_number'])) {
            $data['customer_number'] = 'CUS-'.strtoupper(Str::random(8));
        }

        /** @var Customer $model */
        $model = $this->repository->create($data);

        event(new CustomerCreated($model->load('user')->toArray()));

        return $model;
    }

    public function update(Customer $customer, array $data): Customer
    {
        /** @var Customer $model */
        $model = $this->repository->update($customer, $data);

        return $model->load('user');
    }

    public function delete(Customer $customer): bool
    {
        return $this->repository->delete($customer);
    }
}
