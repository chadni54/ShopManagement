<?php

namespace App\Domains\Shipping\Models;

use Database\Factories\ShipmentFactory;
use App\Shared\Traits\Auditable;
use App\Shared\Traits\HasUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Shipment extends Model
{
    use Auditable;
    use HasFactory;
    use HasUuid;
    use SoftDeletes;

    protected $fillable = [
        'uuid',
        'order_id',
        'carrier',
        'tracking_number',
        'status',
        'shipped_at',
        'delivered_at',
        'address',
    ];

    protected function casts(): array
    {
        return [
            'shipped_at' => 'datetime',
            'delivered_at' => 'datetime',
            'address' => 'array',
        ];
    }

    protected static function newFactory(): ShipmentFactory
    {
        return ShipmentFactory::new();
    }

    public function order(): BelongsTo
    {
        return $this->belongsTo(\App\Domains\Orders\Models\Order::class);
    }
}