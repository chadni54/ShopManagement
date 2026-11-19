<?php

namespace App\Domains\Invoices\Services;

use App\Domains\Invoices\Models\Invoice;
use App\Domains\Invoices\Repositories\InvoiceRepository;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\Request;

class InvoiceService
{
    public function __construct(
        protected InvoiceRepository $repository
    ) {}

    public function list(Request $request): LengthAwarePaginator
    {
        $paginator = $this->repository->paginate(
            $request,
            array (
  0 => 'invoice_number',
  1 => 'status',
),
            array (
  0 => 'order_id',
  1 => 'status',
),
            array (
  0 => 'created_at',
  1 => 'issued_at',
  2 => 'due_at',
  3 => 'total',
)
        );

        $with = array (
  0 => 'order',
);
        if (! empty($with)) {
            $paginator->getCollection()->load($with);
        }

        return $paginator;
    }

    public function show(Invoice $model): Invoice
    {
        $with = array (
  0 => 'order',
);
        if (! empty($with)) {
            $model->load($with);
        }

        return $model;
    }

    public function create(array $data): Invoice
    {
        if (empty($data['invoice_number'])) {
            $data['invoice_number'] = 'INV-'.strtoupper(\Illuminate\Support\Str::random(10));
        }
        if (empty($data['issued_at'])) {
            $data['issued_at'] = now();
        }
        /** @var Invoice $model */
        $model = $this->repository->create($data);

        return $model;
    }

    public function update(Invoice $model, array $data): Invoice
    {
        /** @var Invoice $model */
        $model = $this->repository->update($model, $data);

        return $model;
    }

    public function delete(Invoice $model): bool
    {
        return $this->repository->delete($model);
    }
}