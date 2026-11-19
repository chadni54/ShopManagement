<?php

namespace App\Domains\Support\Services;

use App\Domains\Support\Models\SupportSmsMessage;
use App\Domains\Webhooks\Services\WebhookDispatcher;
use Illuminate\Support\Str;

class SupportSmsService
{
    /**
     * AMS form types from the integration docs. The value is sent as data.form_type.
     *
     * @var array<string, string>
     */
    public const FORM_TYPES = [
        'sms' => 'SMS — Support ticket',
        'support' => 'Support / help — Support ticket',
        'chat' => 'Chat — Support ticket',
        'complaint' => 'Complaint — Support ticket',
        'account_disable' => 'Account disable — Support ticket',
        'privacy' => 'Privacy / GDPR / data deletion — Support ticket + Privacy request',
        'consent' => 'Consent — Privacy request only',
        'compliance_case' => 'Compliance case — Compliance case only',
        'breach' => 'Breach — Data breach only',
        'dpia' => 'DPIA — DPIA only',
    ];

    public function __construct(protected WebhookDispatcher $dispatcher) {}

    /**
     * @param  array<string, mixed>  $input
     */
    public function receive(array $input): SupportSmsMessage
    {
        $messageId = (string) ($input['message_id'] ?? ('shopdemo-sms-'.Str::uuid()));
        $existing = SupportSmsMessage::query()->where('message_id', $messageId)->first();

        if ($existing && $existing->status !== 'failed') {
            return $existing;
        }

        $from = (string) $input['from'];
        $to = (string) ($input['to'] ?? config('services.ams.support_phone'));
        $explicitTicket = filled($input['ticket_uuid'] ?? null) ? (string) $input['ticket_uuid'] : null;
        $thread = null;

        if ($explicitTicket) {
            $thread = SupportSmsMessage::query()
                ->where('direction', 'inbound')
                ->where('ams_ticket_uuid', $explicitTicket)
                ->orderBy('id')
                ->first();
        }

        if (! $thread && ! $explicitTicket) {
            $thread = SupportSmsMessage::query()
                ->where('direction', 'inbound')
                ->where('from_number', $from)
                ->whereNotNull('ams_ticket_uuid')
                ->latest('id')
                ->first();
        }

        $ticketUuid = $explicitTicket ?: $thread?->ams_ticket_uuid;
        $ticketNumber = filled($input['ticket_number'] ?? null)
            ? (string) $input['ticket_number']
            : $thread?->ams_ticket_number;
        $parentId = $input['parent_id'] ?? $thread?->id;
        $formType = array_key_exists((string) ($input['form_type'] ?? ''), self::FORM_TYPES)
            ? (string) $input['form_type']
            : 'sms';
        $event = $formType === 'sms' ? 'support.sms.received' : 'support.message.received';
        $channel = match ($formType) {
            'sms' => 'sms',
            'chat' => 'chat',
            default => 'web',
        };

        $message = $existing ?? SupportSmsMessage::query()->create([
            'message_id' => $messageId,
            'direction' => 'inbound',
            'from_number' => $from,
            'to_number' => $to,
            'body' => (string) $input['body'],
            'customer_name' => $input['customer_name'] ?? null,
            'customer_email' => $input['customer_email'] ?? null,
            'status' => 'pending',
            'ams_ticket_number' => $ticketNumber,
            'ams_ticket_uuid' => $ticketUuid,
            'parent_id' => $parentId,
        ]);

        $data = array_filter([
            'message_id' => $messageId,
            'from' => $from,
            'to' => $to,
            'body' => $message->body,
            'customer_name' => $message->customer_name,
            'customer_email' => $message->customer_email,
            'customer_phone' => $from,
            'channel' => $channel,
            'form_type' => $formType,
            'application_slug' => config('services.ams.application_slug', 'shopdemo-web'),
            'priority' => 'medium',
            'category' => 'customer_support',
            'involves_personal_data' => in_array($formType, ['privacy', 'consent', 'breach'], true),
            'ticket_uuid' => $ticketUuid,
            'ticket_number' => $ticketNumber,
        ], fn ($value) => $value !== null && $value !== '');

        $delivery = $this->dispatcher->dispatchNow($event, $data);
        $decoded = json_decode((string) $delivery?->response_body, true);
        $ticketNumber = data_get($decoded, 'data.ingest.support_ticket_number');
        $ticketUuid = data_get($decoded, 'data.ingest.support_ticket_uuid');
        $forwarded = (bool) $delivery?->success;

        $message->update([
            'status' => $forwarded ? 'forwarded' : 'failed',
            'ams_ticket_number' => $ticketNumber ?: $message->ams_ticket_number,
            'ams_ticket_uuid' => $ticketUuid ?: $message->ams_ticket_uuid,
            'meta' => [
                'form_type' => $formType,
                'ams_event' => $event,
                'ams_delivery_id' => $delivery?->id,
                'response_status' => $delivery?->response_status,
                'forwarded_at' => $forwarded ? now()->toIso8601String() : null,
            ],
        ]);

        return $message->fresh();
    }
}
