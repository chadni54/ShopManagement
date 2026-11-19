<?php

namespace App\Domains\Customers\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateCustomerRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $customerId = $this->route('customer')?->id;

        return [
            'user_id' => ['sometimes', 'exists:users,id'],
            'customer_number' => ['sometimes', 'string', 'max:50', Rule::unique('customers', 'customer_number')->ignore($customerId)],
            'company_name' => ['sometimes', 'nullable', 'string', 'max:255'],
            'billing_address' => ['sometimes', 'nullable', 'string'],
            'shipping_address' => ['sometimes', 'nullable', 'string'],
            'city' => ['sometimes', 'nullable', 'string', 'max:100'],
            'state' => ['sometimes', 'nullable', 'string', 'max:100'],
            'country' => ['sometimes', 'nullable', 'string', 'max:100'],
            'postal_code' => ['sometimes', 'nullable', 'string', 'max:20'],
            'status' => ['sometimes', 'in:active,inactive'],
        ];
    }
}
