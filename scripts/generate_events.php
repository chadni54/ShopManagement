<?php

$root = dirname(__DIR__);

$events = [
    'CustomerCreated' => 'customer.created',
    'OrderCreated' => 'order.created',
    'OrderUpdated' => 'order.updated',
    'OrderCancelled' => 'order.cancelled',
    'PaymentCompleted' => 'payment.completed',
    'PaymentFailed' => 'payment.failed',
    'InventoryUpdated' => 'inventory.updated',
];

foreach ($events as $class => $eventName) {
    $eventPath = "{$root}/app/Events/{$class}.php";
    file_put_contents($eventPath, <<<PHP
<?php

namespace App\\Events;

use Illuminate\\Foundation\\Events\\Dispatchable;
use Illuminate\\Queue\\SerializesModels;

class {$class}
{
    use Dispatchable;
    use SerializesModels;

    public function __construct(public array \$payload) {}
}
PHP);

    $listener = "Dispatch{$class}Webhook";
    $listenerPath = "{$root}/app/Listeners/{$listener}.php";
    file_put_contents($listenerPath, <<<PHP
<?php

namespace App\\Listeners;

use App\\Domains\\Webhooks\\Services\\WebhookDispatcher;
use App\\Events\\{$class};

class {$listener}
{
    public function __construct(protected WebhookDispatcher \$dispatcher) {}

    public function handle({$class} \$event): void
    {
        \$this->dispatcher->dispatch('{$eventName}', \$event->payload);
    }
}
PHP);

    echo "{$class} / {$listener}\n";
}

echo "Events done\n";
