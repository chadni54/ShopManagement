<?php

namespace App\Domains\Products\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreProductRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'category_id' => 'nullable|exists:categories,id',
            'brand_id' => 'nullable|exists:brands,id',
            'sku' => 'required|string|max:100|unique:products,sku',
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:products,slug',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'compare_at_price' => 'nullable|numeric|min:0',
            'cost' => 'nullable|numeric|min:0',
            'currency' => 'sometimes|string|size:3',
            'is_active' => 'sometimes|boolean',
            'attributes' => 'nullable|array',
        ];
    }
}