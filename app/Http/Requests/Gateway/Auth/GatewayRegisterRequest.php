<?php

namespace App\Http\Requests\Gateway\Auth;

use Illuminate\Foundation\Http\FormRequest;

class GatewayRegisterRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|min:3|max:255',
            'email' => 'required|email|max:255',
            'password' => 'required|string|min:6',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'The name is required',
            'name.min' => 'The name must have at least 3 characters',
            'email.required' => 'The email is required',
            'email.email' => 'Please provide a valid email',
            'password.required' => 'The password is required',
            'password.min' => 'The password must have at least 6 characters',
        ];
    }
}
