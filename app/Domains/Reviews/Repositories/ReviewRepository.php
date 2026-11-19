<?php

namespace App\Domains\Reviews\Repositories;

use App\Domains\Reviews\Models\Review;
use App\Shared\Repositories\BaseRepository;

class ReviewRepository extends BaseRepository
{
    public function __construct(Review $model)
    {
        parent::__construct($model);
    }
}