<?php

namespace App\Http\Controllers\Admin;

use App\Domains\Support\Models\SupportSmsMessage;
use App\Domains\Support\Services\SupportSmsService;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\View\View;

class SupportSmsPageController extends Controller
{
    public function index(): View
    {
        return view('admin.support-sms', [
            'title' => 'Support SMS',
            'threads' => $this->threads(),
            'supportPhone' => (string) config('services.ams.support_phone'),
            'users' => User::query()->orderBy('name')->get(['id', 'name', 'email', 'phone']),
            'formTypes' => SupportSmsService::FORM_TYPES,
        ]);
    }

    public function store(Request $request, SupportSmsService $sms): RedirectResponse
    {
        $data = $request->validate([
            'user_id' => ['required', 'exists:users,id'],
            'form_type' => ['required', 'in:'.implode(',', array_keys(SupportSmsService::FORM_TYPES))],
            'from' => ['nullable', 'string', 'max:40'],
            'to' => ['nullable', 'string', 'max:40'],
            'body' => ['required', 'string', 'max:2000'],
        ]);

        $user = User::query()->findOrFail($data['user_id']);
        $from = trim((string) (($data['from'] ?? null) ?: $user->phone));

        if ($from === '') {
            return back()
                ->withInput()
                ->withErrors(['from' => 'This user has no phone number.']);
        }

        $message = $sms->receive([
            'from' => $from,
            'to' => $data['to'] ?? null,
            'body' => $data['body'],
            'customer_name' => $user->name,
            'customer_email' => $user->email,
            'form_type' => $data['form_type'],
        ]);

        if ($message->status !== 'forwarded') {
            return back()
                ->withInput()
                ->with('error', 'AMS did not accept the support SMS.');
        }

        $ticket = $message->ams_ticket_number ? ' Ticket '.$message->ams_ticket_number.'.' : '';

        return redirect()
            ->route('admin.support-sms')
            ->with('success', 'Support SMS forwarded to AMS.'.$ticket);
    }

    public function reply(Request $request, SupportSmsService $sms): RedirectResponse
    {
        $data = $request->validate([
            'thread_id' => ['required', 'exists:support_sms_messages,id'],
            'body' => ['required', 'string', 'max:2000'],
        ]);

        $anchor = SupportSmsMessage::query()->findOrFail($data['thread_id']);
        $from = $anchor->direction === 'inbound' ? $anchor->from_number : $anchor->to_number;
        $to = $anchor->direction === 'inbound' ? $anchor->to_number : $anchor->from_number;

        $message = $sms->receive([
            'from' => (string) $from,
            'to' => $to ?: config('services.ams.support_phone'),
            'body' => $data['body'],
            'customer_name' => $anchor->customer_name,
            'customer_email' => $anchor->customer_email,
            'ticket_uuid' => $anchor->ams_ticket_uuid,
            'ticket_number' => $anchor->ams_ticket_number,
            'parent_id' => $anchor->id,
        ]);

        if ($message->status !== 'forwarded') {
            return back()
                ->withInput()
                ->with('error', 'AMS did not accept the reply.');
        }

        return redirect()
            ->route('admin.support-sms')
            ->with('success', 'Reply sent on '.($message->ams_ticket_number ?: 'this thread').'.');
    }

    /**
     * @return Collection<int, Collection<int, SupportSmsMessage>>
     */
    private function threads(): Collection
    {
        $messages = SupportSmsMessage::query()->orderBy('id')->get();
        $byId = $messages->keyBy('id');

        return $messages
            ->groupBy(function (SupportSmsMessage $message) use ($byId): string {
                $root = $message;
                $guard = 0;
                while ($root->parent_id && $byId->has($root->parent_id) && $guard < 10) {
                    $root = $byId->get($root->parent_id);
                    $guard++;
                }

                return (string) ($root->ams_ticket_uuid
                    ?: $root->ams_ticket_number
                    ?: 'message-'.$root->id);
            })
            ->sortByDesc(fn (Collection $thread) => $thread->max('id'))
            ->values();
    }
}
