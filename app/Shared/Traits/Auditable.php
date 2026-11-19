<?php

namespace App\Shared\Traits;

use App\Domains\Audit\Models\AuditLog;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;

trait Auditable
{
    public static function bootAuditable(): void
    {
        static::created(function (Model $model): void {
            static::writeAudit($model, 'created', null, $model->getAttributes());
        });

        static::updated(function (Model $model): void {
            static::writeAudit($model, 'updated', $model->getOriginal(), $model->getChanges());
        });

        static::deleted(function (Model $model): void {
            static::writeAudit($model, 'deleted', $model->getOriginal(), null);
        });
    }

    protected static function writeAudit(Model $model, string $event, ?array $old, ?array $new): void
    {
        if (! empty($new)) {
            unset($new['updated_at'], $new['created_at'], $new['password'], $new['remember_token']);
        }
        if (! empty($old)) {
            unset($old['updated_at'], $old['created_at'], $old['password'], $old['remember_token']);
        }

        AuditLog::query()->create([
            'auditable_type' => $model::class,
            'auditable_id' => $model->getKey(),
            'event' => $event,
            'user_id' => Auth::id(),
            'ip_address' => Request::ip(),
            'user_agent' => substr((string) Request::userAgent(), 0, 500),
            'old_values' => $old,
            'new_values' => $new,
        ]);
    }
}
