<?php

namespace App\Http\Requests\Gateway\Auth;

use Illuminate\Foundation\Http\FormRequest;

class GatewayLoginRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'email' => 'required|email|max:255',
            'password' => 'required|string|min:6',
        ];
    }

    public function messages(): array
    {
        return [
            'email.required' => 'The email is required',
            'email.email' => 'Please provide a valid email',
            'password.required' => 'The password is required',
            'password.min' => 'The password must have at least 6 characters',
        ];
    }
}
