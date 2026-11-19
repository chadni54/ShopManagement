<?php

namespace App\Domains\Invoices\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateInvoiceRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $id = $this->route('invoice')?->id ?? null;

        return [
            'order_id' => 'sometimes|exists:orders,id',
            'invoice_number' => 'sometimes|string|max:50|unique:invoices,invoice_number',
            'status' => 'sometimes|string|max:50',
            'subtotal' => 'sometimes|numeric|min:0',
            'tax' => 'sometimes|numeric|min:0',
            'total' => 'sometimes|numeric|min:0',
            'issued_at' => 'sometimes|nullable|date',
            'due_at' => 'sometimes|nullable|date',
            'pdf_path' => 'sometimes|nullable|string|max:255',
        ];
    }
}