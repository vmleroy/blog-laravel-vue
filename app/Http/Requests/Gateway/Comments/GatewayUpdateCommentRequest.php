<?php

namespace App\Http\Requests\Gateway\Comments;

use Illuminate\Foundation\Http\FormRequest;

class GatewayUpdateCommentRequest extends FormRequest
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
            'body' => 'required|string|min:3|max:1000',
        ];
    }

    public function messages(): array
    {
        return [
            'id.required' => 'O ID do comentário é obrigatório',
            'id.integer' => 'O ID do comentário deve ser um número inteiro',
            'id.min' => 'O ID do comentário deve ser maior que 0',
            'body.required' => 'O comentário não pode estar vazio',
            'body.min' => 'O comentário deve ter no mínimo 3 caracteres',
            'body.max' => 'O comentário deve ter no máximo 1000 caracteres',
        ];
    }
}
