<?php

namespace Tests\Feature\Support;

use App\Domains\Support\Models\SupportSmsMessage;
use App\Domains\Webhooks\Models\WebhookEndpoint;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class SupportSmsBridgeTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        config([
            'services.ams.webhook_url' => 'http://ams-api.test/api/v1/webhooks/incoming/shopdemo-support',
            'services.ams.webhook_secret' => 'shopdemo-ams-support-secret',
            'services.ams.application_slug' => 'shopdemo-web',
            'services.ams.support_phone' => '+15550001000',
        ]);

        WebhookEndpoint::query()->create([
            'name' => 'AMS Integration',
            'url' => config('services.ams.webhook_url'),
            'secret' => config('services.ams.webhook_secret'),
            'events' => ['support.sms.received'],
            'is_active' => true,
        ]);
    }

    public function test_inbound_sms_is_signed_and_forwarded_to_ams(): void
    {
        Http::fake([
            'ams-api.test/*' => Http::response([
                'success' => true,
                'message' => 'Webhook received successfully.',
                'data' => [
                    'received' => true,
                    'ingest' => [
                        'support_ticket_number' => 'TCK-1042',
                        'support_ticket_uuid' => '11111111-1111-1111-1111-111111111111',
                    ],
                ],
            ], 200),
        ]);

        $response = $this->postJson('/api/v1/support/sms', [
            'message_id' => 'sms-9001',
            'from' => '+8801700000000',
            'to' => '+15550001000',
            'body' => 'Hello, I need help with my order.',
            'customer_name' => 'Rahim Ahmed',
        ]);

        $response->assertCreated()
            ->assertJsonPath('data.message_id', 'sms-9001')
            ->assertJsonPath('data.status', 'forwarded')
            ->assertJsonPath('data.ams_ticket_number', 'TCK-1042');

        Http::assertSent(function ($request): bool {
            $body = $request->body();
            $expected = 'sha256='.hash_hmac('sha256', $body, 'shopdemo-ams-support-secret');
            $payload = json_decode($body, true);

            return $request->url() === 'http://ams-api.test/api/v1/webhooks/incoming/shopdemo-support'
                && $request->hasHeader('X-AMS-Signature', $expected)
                && ($payload['event'] ?? null) === 'support.sms.received'
                && ($payload['data']['message_id'] ?? null) === 'sms-9001'
                && ($payload['data']['application_slug'] ?? null) === 'shopdemo-web'
                && ($payload['data']['from'] ?? null) === '+8801700000000';
        });
    }

    public function test_agent_sms_reply_is_stored_when_signature_matches(): void
    {
        $parent = SupportSmsMessage::query()->create([
            'message_id' => 'sms-9001',
            'direction' => 'inbound',
            'from_number' => '+8801700000000',
            'to_number' => '+15550001000',
            'body' => 'Hello, I need help with my order.',
            'status' => 'forwarded',
            'ams_ticket_number' => 'TCK-1042',
            'ams_ticket_uuid' => '11111111-1111-1111-1111-111111111111',
        ]);

        $payload = [
            'event' => 'support.sms.sent',
            'data' => [
                'ticket_uuid' => $parent->ams_ticket_uuid,
                'ticket_number' => 'TCK-1042',
                'message_uuid' => '22222222-2222-2222-2222-222222222222',
                'visibility' => 'public',
                'body_plain' => 'We found your order.',
                'reply_mode' => 'sms',
                'channel' => 'sms',
                'to' => '+8801700000000',
                'from' => '+15550001000',
            ],
        ];
        $raw = json_encode($payload, JSON_THROW_ON_ERROR);
        $signature = 'sha256='.hash_hmac('sha256', $raw, 'shopdemo-ams-support-secret');

        $this->call(
            'POST',
            '/api/v1/ams/support-replies',
            [],
            [],
            [],
            [
                'CONTENT_TYPE' => 'application/json',
                'HTTP_ACCEPT' => 'application/json',
                'HTTP_X_AMS_SIGNATURE' => $signature,
            ],
            $raw
        )->assertOk()
            ->assertJsonPath('data.reply_mode', 'sms')
            ->assertJsonPath('data.ticket_number', 'TCK-1042');

        $this->assertDatabaseHas('support_sms_messages', [
            'message_id' => '22222222-2222-2222-2222-222222222222',
            'direction' => 'reply',
            'parent_id' => $parent->id,
            'body' => 'We found your order.',
        ]);
    }

    public function test_agent_reply_rejects_a_bad_signature(): void
    {
        $raw = json_encode([
            'event' => 'support.sms.sent',
            'data' => ['body_plain' => 'Nope', 'reply_mode' => 'sms'],
        ], JSON_THROW_ON_ERROR);

        $this->call(
            'POST',
            '/api/v1/ams/support-replies',
            [],
            [],
            [],
            [
                'CONTENT_TYPE' => 'application/json',
                'HTTP_ACCEPT' => 'application/json',
                'HTTP_X_AMS_SIGNATURE' => 'sha256=deadbeef',
            ],
            $raw
        )->assertUnauthorized();
    }
}
