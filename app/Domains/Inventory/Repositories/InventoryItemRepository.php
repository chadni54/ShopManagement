<?php

namespace App\Domains\Inventory\Repositories;

use App\Domains\Inventory\Models\InventoryItem;
use App\Shared\Repositories\BaseRepository;

class InventoryItemRepository extends BaseRepository
{
    public function __construct(InventoryItem $model)
    {
        parent::__construct($model);
    }
}