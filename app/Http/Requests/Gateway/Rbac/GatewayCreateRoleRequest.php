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
            'name.required' => 'O nome da role é obrigatório',
            'name.min' => 'O nome da role deve ter no mínimo 3 caracteres',
            'name.max' => 'O nome da role deve ter no máximo 100 caracteres',
            'description.max' => 'A descrição deve ter no máximo 255 caracteres',
        ];
    }
}
