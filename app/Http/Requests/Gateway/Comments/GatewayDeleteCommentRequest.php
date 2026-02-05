<?php

namespace App\Http\Requests\Gateway\Comments;

use Illuminate\Foundation\Http\FormRequest;

class GatewayDeleteCommentRequest extends FormRequest
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
            'id.required' => 'The comment ID is required',
            'id.integer' => 'The comment ID must be an integer',
            'id.min' => 'The comment ID must be greater than 0',
        ];
    }
}
