<?php

namespace App\Domains\Settings\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateSettingRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'enable_notifications' => ['sometimes', 'boolean'],
            'enable_email' => ['sometimes', 'boolean'],
            'enable_push' => ['sometimes', 'boolean'],
            'language' => ['sometimes', 'string', 'max:10'],
            'timezone' => ['sometimes', 'string', 'max:64'],
            'currency' => ['sometimes', 'string', 'size:3'],
            'preferences' => ['sometimes', 'nullable', 'array'],
        ];
    }
}
