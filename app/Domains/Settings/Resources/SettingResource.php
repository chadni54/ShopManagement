<?php

namespace App\Domains\Settings\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin \App\Domains\Settings\Models\Setting */
class SettingResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->uuid,
            'enable_notifications' => $this->enable_notifications,
            'enable_email' => $this->enable_email,
            'enable_push' => $this->enable_push,
            'language' => $this->language,
            'timezone' => $this->timezone,
            'currency' => $this->currency,
            'preferences' => $this->preferences,
            'created_at' => optional($this->created_at)?->toIso8601String(),
            'updated_at' => optional($this->updated_at)?->toIso8601String(),
        ];
    }
}
