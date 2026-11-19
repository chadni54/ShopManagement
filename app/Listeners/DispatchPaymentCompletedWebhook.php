<?php

namespace App\Listeners;

use App\Domains\Webhooks\Services\WebhookDispatcher;
use App\Events\PaymentCompleted;

class DispatchPaymentCompletedWebhook
{
    public function __construct(protected WebhookDispatcher $dispatcher) {}

    public function handle(PaymentCompleted $event): void
    {
        $this->dispatcher->dispatch('payment.completed', $event->payload);
    }
}