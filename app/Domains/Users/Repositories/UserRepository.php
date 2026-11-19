<?php

namespace App\Domains\Users\Repositories;

use App\Models\User;
use App\Shared\Repositories\BaseRepository;

class UserRepository extends BaseRepository
{
    public function __construct(User $model)
    {
        parent::__construct($model);
    }
}
