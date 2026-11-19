<?php

namespace App\Domains\Payments\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdatePaymentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $id = $this->route('payment')?->id ?? null;

        return [
            'order_id' => 'sometimes|exists:orders,id',
            'payment_number' => 'sometimes|string|max:50|unique:payments,payment_number',
            'method' => 'sometimes|string|max:50',
            'amount' => 'sometimes|numeric|min:0',
            'currency' => 'sometimes|string|size:3',
            'status' => 'sometimes|in:pending,completed,failed,refunded',
            'provider_reference' => 'sometimes|nullable|string|max:255',
            'paid_at' => 'sometimes|nullable|date',
        ];
    }
}