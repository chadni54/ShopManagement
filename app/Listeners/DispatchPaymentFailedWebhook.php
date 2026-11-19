<?php

namespace App\Listeners;

use App\Domains\Webhooks\Services\WebhookDispatcher;
use App\Events\PaymentFailed;

class DispatchPaymentFailedWebhook
{
    public function __construct(protected WebhookDispatcher $dispatcher) {}

    public function handle(PaymentFailed $event): void
    {
        $this->dispatcher->dispatch('payment.failed', $event->payload);
    }
}