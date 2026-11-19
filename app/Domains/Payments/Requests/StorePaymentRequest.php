<?php

namespace App\Domains\Payments\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePaymentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'order_id' => 'required|exists:orders,id',
            'payment_number' => 'nullable|string|max:50|unique:payments,payment_number',
            'method' => 'required|string|max:50',
            'amount' => 'required|numeric|min:0',
            'currency' => 'sometimes|string|size:3',
            'status' => 'sometimes|in:pending,completed,failed,refunded',
            'provider_reference' => 'nullable|string|max:255',
            'paid_at' => 'nullable|date',
        ];
    }
}