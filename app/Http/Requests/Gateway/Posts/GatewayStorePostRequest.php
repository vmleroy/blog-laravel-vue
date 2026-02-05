<?php

namespace App\Http\Requests\Gateway\Posts;

use Illuminate\Foundation\Http\FormRequest;

class GatewayStorePostRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title' => 'required|string|min:5|max:255',
            'content' => 'required|string|min:10',
        ];
    }

    public function messages(): array
    {
        return [
            'title.required' => 'O título é obrigatório',
            'title.min' => 'O título deve ter no mínimo 5 caracteres',
            'title.max' => 'O título deve ter no máximo 255 caracteres',
            'content.required' => 'O conteúdo é obrigatório',
            'content.min' => 'O conteúdo deve ter no mínimo 10 caracteres',
        ];
    }
}
