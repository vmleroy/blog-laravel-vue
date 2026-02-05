<?php

namespace App\Http\Requests\Gateway\Rbac;

use Illuminate\Foundation\Http\FormRequest;

class GatewayCheckAccessRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'user_id' => 'required|integer|min:1',
            'permission_name' => 'required|string|min:3|max:100',
        ];
    }

    public function messages(): array
    {
        return [
            'user_id.required' => 'The user ID is required',
            'user_id.integer' => 'The user ID must be an integer',
            'user_id.min' => 'The user ID must be greater than 0',
            'permission_name.required' => 'The permission name is required',
            'permission_name.min' => 'The permission name must have at least 3 characters',
            'permission_name.max' => 'The permission name must have at most 100 characters',
        ];
    }
}
