<?php

namespace App\Http\Requests\Gateway\Comments;

use Illuminate\Foundation\Http\FormRequest;

class GatewayShowCommentRequest extends FormRequest
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
            'id.required' => 'O ID do comentário é obrigatório',
            'id.integer' => 'O ID do comentário deve ser um número inteiro',
            'id.min' => 'O ID do comentário deve ser maior que 0',
        ];
    }
}
