<?php

namespace App\Domains\Products\Repositories;

use App\Domains\Products\Models\Product;
use App\Shared\Repositories\BaseRepository;

class ProductRepository extends BaseRepository
{
    public function __construct(Product $model)
    {
        parent::__construct($model);
    }
}