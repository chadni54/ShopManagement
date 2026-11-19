<?php

namespace App\Domains\Customers\Models;

use App\Domains\Orders\Models\Order;
use App\Models\User;
use App\Shared\Traits\Auditable;
use App\Shared\Traits\HasUuid;
use Database\Factories\CustomerFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Customer extends Model
{
    use Auditable;
    use HasFactory;
    use HasUuid;
    use SoftDeletes;

    protected $fillable = [
        'uuid',
        'user_id',
        'customer_number',
        'company_name',
        'billing_address',
        'shipping_address',
        'city',
        'state',
        'country',
        'postal_code',
        'status',
    ];

    protected static function newFactory(): CustomerFactory
    {
        return CustomerFactory::new();
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }
}
