<?php

namespace App\Domains\Orders\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin \App\Domains\Orders\Models\Order */
class OrderResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->uuid,
            'customer_id' => $this->customer?->uuid ?? $this->customer_id,
            'order_number' => $this->order_number,
            'status' => $this->status,
            'subtotal' => $this->subtotal,
            'discount' => $this->discount,
            'tax' => $this->tax,
            'shipping_amount' => $this->shipping_amount,
            'total' => $this->total,
            'currency' => $this->currency,
            'coupon_id' => $this->coupon?->uuid ?? $this->coupon_id,
            'notes' => $this->notes,
            'placed_at' => optional($this->placed_at)?->toIso8601String(),
            'items' => OrderItemResource::collection($this->whenLoaded('items')),
            'customer' => $this->whenLoaded('customer', fn () => [
                'id' => $this->customer->uuid,
                'customer_number' => $this->customer->customer_number,
                'company_name' => $this->customer->company_name,
            ]),
            'created_at' => optional($this->created_at)?->toIso8601String(),
            'updated_at' => optional($this->updated_at)?->toIso8601String(),
        ];
    }
}
