<?php

namespace App\Domains\Orders\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreOrderRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'customer_id' => ['required', 'exists:customers,id'],
            'order_number' => ['nullable', 'string', 'max:50', 'unique:orders,order_number'],
            'status' => ['sometimes', 'in:pending,paid,processing,shipped,delivered,cancelled'],
            'discount' => ['sometimes', 'numeric', 'min:0'],
            'tax' => ['sometimes', 'numeric', 'min:0'],
            'shipping_amount' => ['sometimes', 'numeric', 'min:0'],
            'currency' => ['sometimes', 'string', 'size:3'],
            'coupon_id' => ['nullable', 'exists:coupons,id'],
            'notes' => ['nullable', 'string'],
            'placed_at' => ['nullable', 'date'],
            'items' => ['required', 'array', 'min:1'],
            'items.*.product_id' => ['required', 'exists:products,id'],
            'items.*.quantity' => ['required', 'integer', 'min:1'],
            'items.*.unit_price' => ['nullable', 'numeric', 'min:0'],
            'items.*.sku' => ['nullable', 'string', 'max:100'],
            'items.*.name' => ['nullable', 'string', 'max:255'],
        ];
    }
}
