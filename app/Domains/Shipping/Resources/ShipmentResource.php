<?php

namespace App\Domains\Shipping\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin \App\Domains\Shipping\Models\Shipment */
class ShipmentResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->uuid,
            'order_id' => $this->order_id,
            'carrier' => $this->carrier,
            'tracking_number' => $this->tracking_number,
            'status' => $this->status,
            'shipped_at' => optional($this->shipped_at)?->toIso8601String(),
            'delivered_at' => optional($this->delivered_at)?->toIso8601String(),
            'address' => $this->address,
            'created_at' => optional($this->created_at)?->toIso8601String(),
            'updated_at' => optional($this->updated_at)?->toIso8601String(),
        ];
    }
}