<?php

namespace App\Domains\Customers\Repositories;

use App\Domains\Customers\Models\Customer;
use App\Shared\Repositories\BaseRepository;

class CustomerRepository extends BaseRepository
{
    public function __construct(Customer $model)
    {
        parent::__construct($model);
    }
}
