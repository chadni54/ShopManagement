<?php

namespace App\Domains\Coupons\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateCouponRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $id = $this->route('coupon')?->id ?? null;

        return [
            'code' => 'sometimes|string|max:50|unique:coupons,code',
            'type' => 'sometimes|in:percent,fixed',
            'value' => 'sometimes|numeric|min:0',
            'min_order_amount' => 'sometimes|nullable|numeric|min:0',
            'max_uses' => 'sometimes|nullable|integer|min:1',
            'used_count' => 'sometimes|integer|min:0',
            'starts_at' => 'sometimes|nullable|date',
            'ends_at' => 'sometimes|nullable|date',
            'is_active' => 'sometimes|boolean',
        ];
    }
}