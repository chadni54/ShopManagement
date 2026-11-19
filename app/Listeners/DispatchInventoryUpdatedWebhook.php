<?php

namespace App\Listeners;

use App\Domains\Webhooks\Services\WebhookDispatcher;
use App\Events\InventoryUpdated;

class DispatchInventoryUpdatedWebhook
{
    public function __construct(protected WebhookDispatcher $dispatcher) {}

    public function handle(InventoryUpdated $event): void
    {
        $this->dispatcher->dispatch('inventory.updated', $event->payload);
    }
}