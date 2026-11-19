<?php

namespace App\Listeners;

use App\Domains\Webhooks\Services\WebhookDispatcher;
use App\Events\CustomerCreated;

class DispatchCustomerCreatedWebhook
{
    public function __construct(protected WebhookDispatcher $dispatcher) {}

    public function handle(CustomerCreated $event): void
    {
        $this->dispatcher->dispatch('customer.created', $event->payload);
    }
}