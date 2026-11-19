<?php

namespace App\Domains\Webhooks\Jobs;

use App\Domains\Webhooks\Models\WebhookDelivery;
use App\Domains\Webhooks\Models\WebhookEndpoint;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;
use Throwable;

class DispatchWebhookJob implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    public int $tries = 3;

    public function __construct(
        public int $endpointId,
        public string $event,
        public array $payload
    ) {}

    public function handle(): void
    {
        $endpoint = WebhookEndpoint::query()->find($this->endpointId);

        if (! $endpoint || ! $endpoint->is_active) {
            return;
        }

        $body = json_encode($this->payload, JSON_THROW_ON_ERROR);
        $rawSignature = hash_hmac('sha256', $body, (string) $endpoint->secret);

        $delivery = WebhookDelivery::query()->create([
            'webhook_endpoint_id' => $endpoint->id,
            'event' => $this->event,
            'payload' => $this->payload,
            'attempts' => $this->attempts(),
            'success' => false,
        ]);

        try {
            $response = Http::timeout(10)
                ->withHeaders([
                    'Content-Type' => 'application/json',
                    'X-ShopDemo-Event' => $this->event,
                    'X-ShopDemo-Signature' => $rawSignature,
                    'X-AMS-Signature' => 'sha256='.$rawSignature,
                ])
                ->withBody($body, 'application/json')
                ->post($endpoint->url);

            $delivery->update([
                'response_status' => $response->status(),
                'response_body' => substr($response->body(), 0, 2000),
                'attempts' => $this->attempts(),
                'success' => $response->successful(),
                'delivered_at' => $response->successful() ? now() : null,
            ]);

            if (! $response->successful()) {
                $this->release(30);
            }
        } catch (Throwable $e) {
            $delivery->update([
                'response_status' => 0,
                'response_body' => $e->getMessage(),
                'attempts' => $this->attempts(),
                'success' => false,
            ]);

            throw $e;
        }
    }
}
