<?php

namespace App\Http\Requests\Gateway\Auth;

use Illuminate\Foundation\Http\FormRequest;

class GatewayRefreshTokenRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'token' => 'required|string',
        ];
    }

    public function messages(): array
    {
        return [
            'token.required' => 'The token is required',
        ];
    }
}
