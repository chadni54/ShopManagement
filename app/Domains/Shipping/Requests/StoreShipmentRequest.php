<?php

namespace App\Domains\Shipping\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreShipmentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'order_id' => 'required|exists:orders,id',
            'carrier' => 'nullable|string|max:100',
            'tracking_number' => 'nullable|string|max:100',
            'status' => 'sometimes|string|max:50',
            'shipped_at' => 'nullable|date',
            'delivered_at' => 'nullable|date',
            'address' => 'nullable|array',
        ];
    }
}