<?php

namespace App\Domains\Shipping\Repositories;

use App\Domains\Shipping\Models\Shipment;
use App\Shared\Repositories\BaseRepository;

class ShipmentRepository extends BaseRepository
{
    public function __construct(Shipment $model)
    {
        parent::__construct($model);
    }
}