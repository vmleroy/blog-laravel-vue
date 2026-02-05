<?php

namespace App\Http\Requests\Gateway\Posts;

use Illuminate\Foundation\Http\FormRequest;

class GatewayDeletePostRequest extends FormRequest
{
    protected function prepareForValidation(): void
    {
        $this->merge(['id' => $this->route('id')]);
    }

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'id' => 'required|integer|min:1',
        ];
    }

    public function messages(): array
    {
        return [
            'id.required' => 'O ID do post é obrigatório',
            'id.integer' => 'O ID do post deve ser um número inteiro',
            'id.min' => 'O ID do post deve ser maior que 0',
        ];
    }
}
