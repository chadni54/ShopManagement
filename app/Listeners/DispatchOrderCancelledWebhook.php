<?php

namespace App\Listeners;

use App\Domains\Webhooks\Services\WebhookDispatcher;
use App\Events\OrderCancelled;

class DispatchOrderCancelledWebhook
{
    public function __construct(protected WebhookDispatcher $dispatcher) {}

    public function handle(OrderCancelled $event): void
    {
        $this->dispatcher->dispatch('order.cancelled', $event->payload);
    }
}