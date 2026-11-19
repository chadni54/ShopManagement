<?php

namespace App\Domains\Invoices\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreInvoiceRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'order_id' => 'required|exists:orders,id',
            'invoice_number' => 'nullable|string|max:50|unique:invoices,invoice_number',
            'status' => 'sometimes|string|max:50',
            'subtotal' => 'required|numeric|min:0',
            'tax' => 'sometimes|numeric|min:0',
            'total' => 'required|numeric|min:0',
            'issued_at' => 'nullable|date',
            'due_at' => 'nullable|date',
            'pdf_path' => 'nullable|string|max:255',
        ];
    }
}