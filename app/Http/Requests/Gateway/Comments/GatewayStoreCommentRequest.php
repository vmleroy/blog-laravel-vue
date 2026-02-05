<?php

namespace App\Http\Requests\Gateway\Comments;

use Illuminate\Foundation\Http\FormRequest;

class GatewayStoreCommentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'post_id' => 'required|integer|min:1',
            'body' => 'required|string|min:3|max:1000',
        ];
    }

    public function messages(): array
    {
        return [
            'post_id.required' => 'O ID do post é obrigatório',
            'post_id.integer' => 'O ID do post deve ser um número inteiro',
            'post_id.min' => 'O ID do post deve ser maior que 0',
            'body.required' => 'O comentário não pode estar vazio',
            'body.min' => 'O comentário deve ter no mínimo 3 caracteres',
            'body.max' => 'O comentário deve ter no máximo 1000 caracteres',
        ];
    }
}
