<?php

namespace App\Http\Requests\Gateway\Rbac;

use Illuminate\Foundation\Http\FormRequest;

class GatewayCreateRoleRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|min:3|max:100',
            'description' => 'nullable|string|max:255',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'The role name is required',
            'name.min' => 'The role name must have at least 3 characters',
            'name.max' => 'The role name must have at most 100 characters',
            'description.max' => 'The description must have at most 255 characters',
        ];
    }
}
