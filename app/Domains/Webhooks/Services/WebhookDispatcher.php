<?php

namespace App\Domains\Webhooks\Services;

use App\Domains\Webhooks\Jobs\DispatchWebhookJob;
use App\Domains\Webhooks\Models\WebhookDelivery;
use App\Domains\Webhooks\Models\WebhookEndpoint;
use Illuminate\Support\Collection;

class WebhookDispatcher
{
    public function dispatch(string $event, array $payload): void
    {
        foreach ($this->matchingEndpoints($event) as $endpoint) {
            DispatchWebhookJob::dispatch($endpoint->id, $event, $this->envelope($event, $payload));
        }
    }

    public function dispatchNow(string $event, array $payload): ?WebhookDelivery
    {
        $endpoint = $this->matchingEndpoints($event)->first();

        if (! $endpoint) {
            return null;
        }

        (new DispatchWebhookJob($endpoint->id, $event, $this->envelope($event, $payload)))->handle();

        return WebhookDelivery::query()
            ->where('webhook_endpoint_id', $endpoint->id)
            ->where('event', $event)
            ->latest('id')
            ->first();
    }

    /**
     * @return Collection<int, WebhookEndpoint>
     */
    protected function matchingEndpoints(string $event): Collection
    {
        return WebhookEndpoint::query()
            ->where('is_active', true)
            ->get()
            ->filter(function (WebhookEndpoint $endpoint) use ($event): bool {
                $events = $endpoint->events ?? [];

                return in_array('*', $events, true) || in_array($event, $events, true);
            })
            ->values();
    }

    /**
     * @param  array<string, mixed>  $payload
     * @return array<string, mixed>
     */
    protected function envelope(string $event, array $payload): array
    {
        return [
            'event' => $event,
            'timestamp' => now()->toIso8601String(),
            'data' => $payload,
        ];
    }
}
