<?php

namespace App\Domains\Settings\Services;

use App\Domains\Settings\Models\Setting;
use App\Models\User;

class SettingService
{
    public function show(User $user): Setting
    {
        return Setting::query()->firstOrCreate(
            ['user_id' => $user->id],
            [
                'enable_notifications' => true,
                'enable_email' => true,
                'enable_push' => true,
                'language' => 'en',
                'timezone' => 'UTC',
                'currency' => 'USD',
            ]
        );
    }

    public function update(User $user, array $data): Setting
    {
        $setting = $this->show($user);
        $setting->update($data);

        return $setting->fresh();
    }
}
