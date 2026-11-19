<?php

namespace App\Domains\Reviews\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateReviewRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $id = $this->route('review')?->id ?? null;

        return [
            'product_id' => 'sometimes|exists:products,id',
            'customer_id' => 'sometimes|exists:customers,id',
            'rating' => 'sometimes|integer|min:1|max:5',
            'title' => 'sometimes|nullable|string|max:255',
            'body' => 'sometimes|nullable|string',
            'is_approved' => 'sometimes|boolean',
            'reviewed_at' => 'sometimes|nullable|date',
        ];
    }
}