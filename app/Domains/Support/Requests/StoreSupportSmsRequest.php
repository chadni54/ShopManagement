<?php

namespace App\Domains\Support\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreSupportSmsRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'message_id' => ['nullable', 'string', 'max:100'],
            'from' => ['required', 'string', 'max:40'],
            'to' => ['nullable', 'string', 'max:40'],
            'body' => ['required', 'string', 'max:2000'],
            'customer_name' => ['nullable', 'string', 'max:255'],
            'customer_email' => ['nullable', 'email', 'max:255'],
        ];
    }
}
