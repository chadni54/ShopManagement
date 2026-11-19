<?php

namespace App\Domains\Inventory\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreInventoryItemRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:0',
            'reserved_quantity' => 'sometimes|integer|min:0',
            'warehouse' => 'sometimes|string|max:100',
            'low_stock_threshold' => 'sometimes|integer|min:0',
            'status' => 'sometimes|string|in:in_stock,low_stock,out_of_stock',
        ];
    }
}