<?php

namespace App\Domains\Shipping\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateShipmentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $id = $this->route('shipping')?->id ?? null;

        return [
            'order_id' => 'sometimes|exists:orders,id',
            'carrier' => 'sometimes|nullable|string|max:100',
            'tracking_number' => 'sometimes|nullable|string|max:100',
            'status' => 'sometimes|string|max:50',
            'shipped_at' => 'sometimes|nullable|date',
            'delivered_at' => 'sometimes|nullable|date',
            'address' => 'sometimes|nullable|array',
        ];
    }
}