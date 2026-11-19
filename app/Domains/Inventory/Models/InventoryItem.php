<?php

namespace App\Domains\Inventory\Models;

use Database\Factories\InventoryItemFactory;
use App\Shared\Traits\Auditable;
use App\Shared\Traits\HasUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class InventoryItem extends Model
{
    use Auditable;
    use HasFactory;
    use HasUuid;
    use SoftDeletes;

    protected $fillable = [
        'uuid',
        'product_id',
        'quantity',
        'reserved_quantity',
        'warehouse',
        'low_stock_threshold',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'quantity' => 'integer',
            'reserved_quantity' => 'integer',
            'low_stock_threshold' => 'integer',
        ];
    }

    protected static function newFactory(): InventoryItemFactory
    {
        return InventoryItemFactory::new();
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(\App\Domains\Products\Models\Product::class);
    }
}