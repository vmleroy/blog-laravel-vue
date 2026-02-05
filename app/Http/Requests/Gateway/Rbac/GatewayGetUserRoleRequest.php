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
            'user_id.required' => 'O ID do usuário é obrigatório',
            'user_id.integer' => 'O ID do usuário deve ser um número inteiro',
            'user_id.min' => 'O ID do usuário deve ser maior que 0',
        ];
    }
}
