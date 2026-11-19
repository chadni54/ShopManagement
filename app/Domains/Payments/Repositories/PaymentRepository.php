<?php

namespace App\Domains\Payments\Repositories;

use App\Domains\Payments\Models\Payment;
use App\Shared\Repositories\BaseRepository;

class PaymentRepository extends BaseRepository
{
    public function __construct(Payment $model)
    {
        parent::__construct($model);
    }
}