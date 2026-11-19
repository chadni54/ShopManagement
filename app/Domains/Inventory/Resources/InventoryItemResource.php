<?php

namespace App\Domains\Inventory\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin \App\Domains\Inventory\Models\InventoryItem */
class InventoryItemResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->uuid,
            'product_id' => $this->product_id,
            'quantity' => $this->quantity,
            'reserved_quantity' => $this->reserved_quantity,
            'warehouse' => $this->warehouse,
            'low_stock_threshold' => $this->low_stock_threshold,
            'status' => $this->status,
            'created_at' => optional($this->created_at)?->toIso8601String(),
            'updated_at' => optional($this->updated_at)?->toIso8601String(),
        ];
    }
}