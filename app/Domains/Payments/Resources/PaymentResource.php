<?php

namespace App\Domains\Payments\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin \App\Domains\Payments\Models\Payment */
class PaymentResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->uuid,
            'order_id' => $this->order_id,
            'payment_number' => $this->payment_number,
            'method' => $this->method,
            'amount' => $this->amount,
            'currency' => $this->currency,
            'status' => $this->status,
            'provider_reference' => $this->provider_reference,
            'paid_at' => optional($this->paid_at)?->toIso8601String(),
            'created_at' => optional($this->created_at)?->toIso8601String(),
            'updated_at' => optional($this->updated_at)?->toIso8601String(),
        ];
    }
}