<?php

namespace App\Domains\Orders\Repositories;

use App\Domains\Orders\Models\Order;
use App\Shared\Repositories\BaseRepository;

class OrderRepository extends BaseRepository
{
    public function __construct(Order $model)
    {
        parent::__construct($model);
    }
}
