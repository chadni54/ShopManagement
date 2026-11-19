<?php

namespace App\Domains\Coupons\Repositories;

use App\Domains\Coupons\Models\Coupon;
use App\Shared\Repositories\BaseRepository;

class CouponRepository extends BaseRepository
{
    public function __construct(Coupon $model)
    {
        parent::__construct($model);
    }
}