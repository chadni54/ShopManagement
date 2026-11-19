<?php

namespace App\Domains\Payments\Services;

use App\Domains\Payments\Models\Payment;
use App\Domains\Payments\Repositories\PaymentRepository;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\Request;

class PaymentService
{
    public function __construct(
        protected PaymentRepository $repository
    ) {}

    public function list(Request $request): LengthAwarePaginator
    {
        $paginator = $this->repository->paginate(
            $request,
            array (
  0 => 'payment_number',
  1 => 'provider_reference',
  2 => 'method',
),
            array (
  0 => 'order_id',
  1 => 'status',
  2 => 'method',
  3 => 'currency',
),
            array (
  0 => 'created_at',
  1 => 'amount',
  2 => 'paid_at',
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

    public function show(Payment $model): Payment
    {
        $with = array (
  0 => 'order',
);
        if (! empty($with)) {
            $model->load($with);
        }

        return $model;
    }

    public function create(array $data): Payment
    {
        if (empty($data['payment_number'])) {
            $data['payment_number'] = 'PAY-'.strtoupper(\Illuminate\Support\Str::random(10));
        }
        if (($data['status'] ?? 'pending') === 'completed' && empty($data['paid_at'])) {
            $data['paid_at'] = now();
        }
        /** @var Payment $model */
        $model = $this->repository->create($data);


        $status = $model->status;
        if ($status === 'completed') {
            event(new \App\Events\PaymentCompleted($model->toArray()));
        } elseif ($status === 'failed') {
            event(new \App\Events\PaymentFailed($model->toArray()));
        }
        return $model;
    }

    public function update(Payment $model, array $data): Payment
    {
        /** @var Payment $model */
        $model = $this->repository->update($model, $data);


        $status = $model->status;
        if ($status === 'completed') {
            event(new \App\Events\PaymentCompleted($model->toArray()));
        } elseif ($status === 'failed') {
            event(new \App\Events\PaymentFailed($model->toArray()));
        }
        return $model;
    }

    public function delete(Payment $model): bool
    {
        return $this->repository->delete($model);
    }
}