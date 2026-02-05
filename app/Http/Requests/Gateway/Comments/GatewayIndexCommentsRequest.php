<?php

namespace App\Http\Requests\Gateway\Comments;

use Illuminate\Foundation\Http\FormRequest;

class GatewayIndexCommentsRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'post_id' => 'sometimes|integer|min:1',
        ];
    }

    public function messages(): array
    {
        return [
            'post_id.integer' => 'O ID do post deve ser um número inteiro',
            'post_id.min' => 'O ID do post deve ser maior que 0',
        ];
    }
}
