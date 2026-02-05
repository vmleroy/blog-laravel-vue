<?php

namespace App\Http\Requests\Gateway\Rbac;

use Illuminate\Foundation\Http\FormRequest;

class GatewayAssignRoleRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'user_id' => 'required|integer|min:1',
            'role_id' => 'required|integer|min:1',
        ];
    }

    public function messages(): array
    {
        return [
            'user_id.required' => 'The user ID is required',
            'user_id.integer' => 'The user ID must be an integer',
            'user_id.min' => 'The user ID must be greater than 0',
            'role_id.required' => 'The role ID is required',
            'role_id.integer' => 'The role ID must be an integer',
            'role_id.min' => 'The role ID must be greater than 0',
        ];
    }
}
