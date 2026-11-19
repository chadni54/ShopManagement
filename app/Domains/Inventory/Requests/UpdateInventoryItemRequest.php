<?php

namespace App\Domains\Inventory\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateInventoryItemRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $id = $this->route('inventory')?->id ?? null;

        return [
            'product_id' => 'sometimes|exists:products,id',
            'quantity' => 'sometimes|integer|min:0',
            'reserved_quantity' => 'sometimes|integer|min:0',
            'warehouse' => 'sometimes|string|max:100',
            'low_stock_threshold' => 'sometimes|integer|min:0',
            'status' => 'sometimes|string|in:in_stock,low_stock,out_of_stock',
        ];
    }
}