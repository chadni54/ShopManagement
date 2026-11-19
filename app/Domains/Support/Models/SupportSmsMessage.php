<?php

namespace App\Domains\Support\Models;

use App\Shared\Traits\HasUuid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SupportSmsMessage extends Model
{
    use HasUuid;

    protected $fillable = [
        'uuid',
        'message_id',
        'direction',
        'from_number',
        'to_number',
        'body',
        'customer_name',
        'customer_email',
        'status',
        'ams_ticket_number',
        'ams_ticket_uuid',
        'parent_id',
        'meta',
    ];

    protected function casts(): array
    {
        return [
            'meta' => 'array',
        ];
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(self::class, 'parent_id');
    }
}
