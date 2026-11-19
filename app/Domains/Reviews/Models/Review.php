<?php

namespace App\Domains\Reviews\Models;

use Database\Factories\ReviewFactory;
use App\Shared\Traits\Auditable;
use App\Shared\Traits\HasUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Review extends Model
{
    use Auditable;
    use HasFactory;
    use HasUuid;
    use SoftDeletes;

    protected $fillable = [
        'uuid',
        'product_id',
        'customer_id',
        'rating',
        'title',
        'body',
        'is_approved',
        'reviewed_at',
    ];

    protected function casts(): array
    {
        return [
            'rating' => 'integer',
            'is_approved' => 'boolean',
            'reviewed_at' => 'datetime',
        ];
    }

    protected static function newFactory(): ReviewFactory
    {
        return ReviewFactory::new();
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(\App\Domains\Products\Models\Product::class);
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(\App\Domains\Customers\Models\Customer::class);
    }
}