<?php

namespace App\Providers;

use App\Events\CustomerCreated;
use App\Events\InventoryUpdated;
use App\Events\OrderCancelled;
use App\Events\OrderCreated;
use App\Events\OrderUpdated;
use App\Events\PaymentCompleted;
use App\Events\PaymentFailed;
use App\Listeners\DispatchCustomerCreatedWebhook;
use App\Listeners\DispatchInventoryUpdatedWebhook;
use App\Listeners\DispatchOrderCancelledWebhook;
use App\Listeners\DispatchOrderCreatedWebhook;
use App\Listeners\DispatchOrderUpdatedWebhook;
use App\Listeners\DispatchPaymentCompletedWebhook;
use App\Listeners\DispatchPaymentFailedWebhook;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        Event::listen(CustomerCreated::class, DispatchCustomerCreatedWebhook::class);
        Event::listen(OrderCreated::class, DispatchOrderCreatedWebhook::class);
        Event::listen(OrderUpdated::class, DispatchOrderUpdatedWebhook::class);
        Event::listen(OrderCancelled::class, DispatchOrderCancelledWebhook::class);
        Event::listen(PaymentCompleted::class, DispatchPaymentCompletedWebhook::class);
        Event::listen(PaymentFailed::class, DispatchPaymentFailedWebhook::class);
        Event::listen(InventoryUpdated::class, DispatchInventoryUpdatedWebhook::class);
    }
}
