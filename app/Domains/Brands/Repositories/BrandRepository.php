<?php

namespace App\Domains\Brands\Repositories;

use App\Domains\Brands\Models\Brand;
use App\Shared\Repositories\BaseRepository;

class BrandRepository extends BaseRepository
{
    public function __construct(Brand $model)
    {
        parent::__construct($model);
    }
}