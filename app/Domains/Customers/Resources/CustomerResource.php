<?php

namespace App\Domains\Customers\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin \App\Domains\Customers\Models\Customer */
class CustomerResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->uuid,
            'user_id' => $this->user?->uuid ?? $this->user_id,
            'customer_number' => $this->customer_number,
            'company_name' => $this->company_name,
            'billing_address' => $this->billing_address,
            'shipping_address' => $this->shipping_address,
            'city' => $this->city,
            'state' => $this->state,
            'country' => $this->country,
            'postal_code' => $this->postal_code,
            'status' => $this->status,
            'user' => $this->whenLoaded('user', fn () => [
                'id' => $this->user->uuid,
                'name' => $this->user->name,
                'email' => $this->user->email,
            ]),
            'created_at' => optional($this->created_at)?->toIso8601String(),
            'updated_at' => optional($this->updated_at)?->toIso8601String(),
        ];
    }
}
