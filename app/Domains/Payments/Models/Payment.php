<?php

namespace App\Domains\Payments\Models;

use Database\Factories\PaymentFactory;
use App\Shared\Traits\Auditable;
use App\Shared\Traits\HasUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Payment extends Model
{
    use Auditable;
    use HasFactory;
    use HasUuid;
    use SoftDeletes;

    protected $fillable = [
        'uuid',
        'order_id',
        'payment_number',
        'method',
        'amount',
        'currency',
        'status',
        'provider_reference',
        'paid_at',
    ];

    protected function casts(): array
    {
        return [
            'amount' => 'decimal:2',
            'paid_at' => 'datetime',
        ];
    }

    protected static function newFactory(): PaymentFactory
    {
        return PaymentFactory::new();
    }

    public function order(): BelongsTo
    {
        return $this->belongsTo(\App\Domains\Orders\Models\Order::class);
    }
}