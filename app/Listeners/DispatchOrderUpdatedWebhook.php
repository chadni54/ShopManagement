<?php

namespace App\Listeners;

use App\Domains\Webhooks\Services\WebhookDispatcher;
use App\Events\OrderUpdated;

class DispatchOrderUpdatedWebhook
{
    public function __construct(protected WebhookDispatcher $dispatcher) {}

    public function handle(OrderUpdated $event): void
    {
        $this->dispatcher->dispatch('order.updated', $event->payload);
    }
}