<?php

namespace App\Domains\Categories\Repositories;

use App\Domains\Categories\Models\Category;
use App\Shared\Repositories\BaseRepository;

class CategoryRepository extends BaseRepository
{
    public function __construct(Category $model)
    {
        parent::__construct($model);
    }
}