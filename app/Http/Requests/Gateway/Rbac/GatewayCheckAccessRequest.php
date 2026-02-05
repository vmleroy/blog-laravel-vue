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
            'user_id.required' => 'O ID do usuário é obrigatório',
            'user_id.integer' => 'O ID do usuário deve ser um número inteiro',
            'user_id.min' => 'O ID do usuário deve ser maior que 0',
            'permission_name.required' => 'O nome da permissão é obrigatório',
            'permission_name.min' => 'O nome da permissão deve ter no mínimo 3 caracteres',
            'permission_name.max' => 'O nome da permissão deve ter no máximo 100 caracteres',
        ];
    }
}
