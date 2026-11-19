<?php

namespace App\Listeners;

use App\Domains\Webhooks\Services\WebhookDispatcher;
use App\Events\OrderCreated;

class DispatchOrderCreatedWebhook
{
    public function __construct(protected WebhookDispatcher $dispatcher) {}

    public function handle(OrderCreated $event): void
    {
        $this->dispatcher->dispatch('order.created', $event->payload);
    }
}