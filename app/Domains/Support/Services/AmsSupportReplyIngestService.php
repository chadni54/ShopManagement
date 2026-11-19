<?php

namespace App\Domains\Support\Services;

use App\Domains\Support\Models\SupportSmsMessage;
use App\Shared\Http\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Throwable;

class AmsSupportReplyIngestService
{
    public function ingest(Request $request): JsonResponse
    {
        $raw = $request->getContent();
        $secret = (string) config('services.ams.webhook_secret');
        $provided = (string) $request->header('X-AMS-Signature', '');

        if (! $this->signatureValid($raw, $secret, $provided)) {
            return ApiResponse::error('Webhook signature verification failed.', 401);
        }

        try {
            $payload = json_decode($raw, true, 512, JSON_THROW_ON_ERROR);
        } catch (Throwable) {
            return ApiResponse::error('Invalid JSON payload.', 422);
        }

        $event = (string) ($payload['event'] ?? $payload['event_name'] ?? '');
        if (! in_array($event, ['support.sms.sent', 'support.reply.sent'], true)) {
            return ApiResponse::success(['ignored' => true, 'event' => $event], 'Event ignored');
        }

        $data = is_array($payload['data'] ?? null) ? $payload['data'] : [];
        $visibility = strtolower((string) ($data['visibility'] ?? 'public'));
        if ($visibility !== '' && $visibility !== 'public') {
            return ApiResponse::success(['ignored' => true, 'reason' => 'non_public'], 'Non-public reply ignored');
        }

        $replyMode = $this->replyMode($event, $data);
        if ($replyMode !== 'sms') {
            return ApiResponse::success(['ignored' => true, 'reply_mode' => $replyMode], 'Non-SMS reply ignored');
        }

        $body = trim((string) ($data['body_plain'] ?? strip_tags((string) ($data['body'] ?? $data['message'] ?? ''))));
        if ($body === '') {
            return ApiResponse::error('Reply body is required.', 422);
        }

        $messageKey = (string) ($data['message_uuid'] ?? $data['message_id'] ?? '');
        if ($messageKey !== '') {
            $existing = SupportSmsMessage::query()->where('message_id', $messageKey)->first();
            if ($existing) {
                return ApiResponse::success([
                    'idempotent' => true,
                    'message_id' => $existing->message_id,
                ], 'Duplicate reply ignored');
            }
        }

        $parent = $this->parent($data);
        $customerPhone = (string) ($data['to'] ?? $data['customer_phone'] ?? $parent?->from_number ?? '');
        $from = (string) ($data['from'] ?? config('services.ams.support_phone'));

        $reply = SupportSmsMessage::query()->create([
            'message_id' => $messageKey !== '' ? $messageKey : 'ams-reply-'.($data['ticket_uuid'] ?? uniqid()),
            'direction' => 'reply',
            'from_number' => $from,
            'to_number' => $customerPhone,
            'body' => $body,
            'status' => 'stored',
            'ams_ticket_number' => $data['ticket_number'] ?? $parent?->ams_ticket_number,
            'ams_ticket_uuid' => $data['ticket_uuid'] ?? $parent?->ams_ticket_uuid,
            'parent_id' => $parent?->id,
            'meta' => [
                'source' => 'ams_agent_reply',
                'event' => $event,
                'reply_mode' => 'sms',
            ],
        ]);

        return ApiResponse::success([
            'message_id' => $reply->message_id,
            'ticket_number' => $reply->ams_ticket_number,
            'reply_mode' => 'sms',
        ], 'AMS SMS reply stored');
    }

    /**
     * @param  array<string, mixed>  $data
     */
    protected function replyMode(string $event, array $data): string
    {
        $explicit = strtolower((string) ($data['reply_mode'] ?? ''));
        if (in_array($explicit, ['sms', 'live_chat'], true)) {
            return $explicit;
        }

        $channel = strtolower((string) ($data['channel'] ?? $data['source'] ?? ''));
        if ($event === 'support.sms.sent' || $channel === 'sms') {
            return 'sms';
        }

        return 'live_chat';
    }

    /**
     * @param  array<string, mixed>  $data
     */
    protected function parent(array $data): ?SupportSmsMessage
    {
        if (! empty($data['ticket_uuid'])) {
            $byTicket = SupportSmsMessage::query()
                ->where('direction', 'inbound')
                ->where('ams_ticket_uuid', $data['ticket_uuid'])
                ->latest('id')
                ->first();
            if ($byTicket) {
                return $byTicket;
            }
        }

        $externalId = (string) ($data['external_message_id'] ?? '');
        if ($externalId !== '') {
            $byMessage = SupportSmsMessage::query()->where('message_id', $externalId)->first();
            if ($byMessage) {
                return $byMessage->direction === 'inbound' ? $byMessage : $byMessage->parent;
            }
        }

        $phone = (string) ($data['to'] ?? $data['customer_phone'] ?? '');
        if ($phone === '') {
            return null;
        }

        return SupportSmsMessage::query()
            ->where('direction', 'inbound')
            ->where('from_number', $phone)
            ->latest('id')
            ->first();
    }

    protected function signatureValid(string $raw, string $secret, string $provided): bool
    {
        if ($secret === '' || $provided === '') {
            return false;
        }

        $expected = hash_hmac('sha256', $raw, $secret);
        $normalized = str_contains($provided, '=')
            ? substr($provided, strpos($provided, '=') + 1)
            : $provided;

        return hash_equals($expected, strtolower(trim($normalized)));
    }
}
