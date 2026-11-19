<?php

namespace App\Domains\Settings\Models;

use App\Models\User;
use App\Shared\Traits\Auditable;
use App\Shared\Traits\HasUuid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Setting extends Model
{
    use Auditable;
    use HasUuid;
    use SoftDeletes;

    protected $fillable = [
        'uuid',
        'user_id',
        'enable_notifications',
        'enable_email',
        'enable_push',
        'language',
        'timezone',
        'currency',
        'preferences',
    ];

    protected function casts(): array
    {
        return [
            'enable_notifications' => 'boolean',
            'enable_email' => 'boolean',
            'enable_push' => 'boolean',
            'preferences' => 'array',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
