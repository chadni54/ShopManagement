<?php

namespace App\Domains\Notifications\Services;

use App\Domains\Notifications\Models\AppNotification;
use App\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\Request;

class NotificationService
{
    public function list(User $user, Request $request): LengthAwarePaginator
    {
        $perPage = min(max((int) $request->input('per_page', 15), 1), 100);

        return AppNotification::query()
            ->where('user_id', $user->id)
            ->orderByDesc('created_at')
            ->paginate($perPage);
    }

    public function markRead(User $user, AppNotification $notification): AppNotification
    {
        abort_unless($notification->user_id === $user->id, 404);

        if (! $notification->read_at) {
            $notification->update(['read_at' => now()]);
        }

        return $notification->fresh();
    }

    public function markAllRead(User $user): int
    {
        return AppNotification::query()
            ->where('user_id', $user->id)
            ->whereNull('read_at')
            ->update(['read_at' => now()]);
    }
}
