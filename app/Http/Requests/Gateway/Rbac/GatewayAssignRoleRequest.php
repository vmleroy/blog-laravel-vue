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
            'user_id.required' => 'O ID do usuário é obrigatório',
            'user_id.integer' => 'O ID do usuário deve ser um número inteiro',
            'user_id.min' => 'O ID do usuário deve ser maior que 0',
            'role_id.required' => 'O ID da role é obrigatório',
            'role_id.integer' => 'O ID da role deve ser um número inteiro',
            'role_id.min' => 'O ID da role deve ser maior que 0',
        ];
    }
}
