<?php

namespace Tests\Feature\Admin;

use App\Domains\Support\Models\SupportSmsMessage;
use App\Domains\Webhooks\Models\WebhookEndpoint;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class SupportSmsPageTest extends TestCase
{
    use RefreshDatabase;

    public function test_dashboard_lists_support_sms(): void
    {
        $admin = User::factory()->admin()->create([
            'email' => 'admin@shopdemo.test',
            'password' => 'password',
            'status' => 'active',
        ]);

        $inbound = SupportSmsMessage::query()->create([
            'message_id' => 'sms-dash-1',
            'direction' => 'inbound',
            'from_number' => '+8801700000000',
            'to_number' => '+15550001000',
            'customer_name' => 'Rahim Ahmed',
            'body' => 'Where is my parcel?',
            'status' => 'forwarded',
            'ams_ticket_number' => 'SUP-100',
            'ams_ticket_uuid' => '11111111-1111-1111-1111-111111111111',
        ]);

        SupportSmsMessage::query()->create([
            'message_id' => 'sms-dash-reply',
            'direction' => 'reply',
            'from_number' => '+15550001000',
            'to_number' => '+8801700000000',
            'body' => 'It ships tomorrow.',
            'status' => 'stored',
            'ams_ticket_number' => 'SUP-100',
            'ams_ticket_uuid' => '11111111-1111-1111-1111-111111111111',
            'parent_id' => $inbound->id,
        ]);

        $this->post('/login', [
            'email' => 'admin@shopdemo.test',
            'password' => 'password',
        ]);

        $this->get('/dashboard')
            ->assertOk()
            ->assertSee('Support SMS')
            ->assertSee('Where is my parcel?')
            ->assertSee('SUP-100');

        $this->get('/dashboard/support-sms')
            ->assertOk()
            ->assertSeeInOrder(['Where is my parcel?', 'It ships tomorrow.'])
            ->assertSee('sms-bubble-customer', false)
            ->assertSee('sms-bubble-agent', false)
            ->assertSee('Write a reply')
            ->assertSee('Type')
            ->assertSee('Complaint — Support ticket')
            ->assertSee('Select a user')
            ->assertSee($admin->name)
            ->assertSee($admin->email);
    }

    public function test_dashboard_form_forwards_sms_to_ams(): void
    {
        User::factory()->admin()->create([
            'email' => 'admin@shopdemo.test',
            'password' => 'password',
            'status' => 'active',
        ]);

        $customer = User::factory()->customer()->create([
            'name' => 'Karim Hasan',
            'phone' => '+8801711111111',
            'email' => 'karim@shopdemo.test',
        ]);

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
            'events' => ['support.sms.received', 'support.message.received'],
            'is_active' => true,
        ]);

        Http::fake([
            'ams-api.test/*' => Http::response([
                'success' => true,
                'data' => [
                    'ingest' => [
                        'support_ticket_number' => 'SUP-200',
                        'support_ticket_uuid' => '11111111-1111-1111-1111-111111111111',
                    ],
                ],
            ], 200),
        ]);

        $this->post('/login', [
            'email' => 'admin@shopdemo.test',
            'password' => 'password',
        ]);

        $this->post('/dashboard/support-sms', [
            'user_id' => $customer->id,
            'form_type' => 'complaint',
            'to' => '+15550001000',
            'body' => 'Need a refund.',
        ])->assertRedirect(route('admin.support-sms'));

        Http::assertSent(function ($request): bool {
            $payload = json_decode($request->body(), true);

            return ($payload['event'] ?? null) === 'support.message.received'
                && ($payload['data']['form_type'] ?? null) === 'complaint'
                && ($payload['data']['involves_personal_data'] ?? null) === false;
        });

        $this->get('/dashboard/support-sms')
            ->assertOk()
            ->assertSee('Need a refund.')
            ->assertSee('Karim Hasan')
            ->assertSee('SUP-200');
    }

    public function test_thread_reply_stays_on_the_same_ticket(): void
    {
        User::factory()->admin()->create([
            'email' => 'admin@shopdemo.test',
            'password' => 'password',
            'status' => 'active',
        ]);

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

        $inbound = SupportSmsMessage::query()->create([
            'message_id' => 'sms-anya',
            'direction' => 'inbound',
            'from_number' => '+15550000014',
            'to_number' => '+15550001000',
            'customer_name' => 'Anya Cronin',
            'body' => 'hellow i am anya',
            'status' => 'forwarded',
            'ams_ticket_number' => 'SUP-20261026-00002',
            'ams_ticket_uuid' => '22222222-2222-2222-2222-222222222222',
        ]);

        Http::fake([
            'ams-api.test/*' => Http::response([
                'success' => true,
                'data' => [
                    'ingest' => [
                        'support_ticket_number' => 'SUP-20261026-00002',
                        'support_ticket_uuid' => '22222222-2222-2222-2222-222222222222',
                    ],
                ],
            ], 200),
        ]);

        $this->post('/login', [
            'email' => 'admin@shopdemo.test',
            'password' => 'password',
        ]);

        $this->post('/dashboard/support-sms/reply', [
            'thread_id' => $inbound->id,
            'body' => 'Can you check the delivery again?',
        ])->assertRedirect(route('admin.support-sms'));

        $this->assertDatabaseHas('support_sms_messages', [
            'parent_id' => $inbound->id,
            'body' => 'Can you check the delivery again?',
            'ams_ticket_number' => 'SUP-20261026-00002',
            'direction' => 'inbound',
        ]);

        Http::assertSent(function ($request): bool {
            $payload = json_decode($request->body(), true);

            return ($payload['data']['ticket_uuid'] ?? null) === '22222222-2222-2222-2222-222222222222'
                && ($payload['data']['body'] ?? null) === 'Can you check the delivery again?';
        });
    }
}
