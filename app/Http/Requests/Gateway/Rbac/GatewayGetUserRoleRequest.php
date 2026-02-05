<?php

namespace App\Http\Requests\Gateway\Rbac;

use Illuminate\Foundation\Http\FormRequest;

class GatewayGetUserRoleRequest extends FormRequest
{
    protected function prepareForValidation(): void
    {
        $this->merge(['user_id' => $this->route('userId')]);
    }

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'user_id' => 'required|integer|min:1',
        ];
    }

    public function messages(): array
    {
        return [
            'user_id.required' => 'The user ID is required',
            'user_id.integer' => 'The user ID must be an integer',
            'user_id.min' => 'The user ID must be greater than 0',
        ];
    }
}
