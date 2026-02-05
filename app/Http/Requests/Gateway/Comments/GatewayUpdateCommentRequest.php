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
            'id.required' => 'The comment ID is required',
            'id.integer' => 'The comment ID must be an integer',
            'id.min' => 'The comment ID must be greater than 0',
            'body.required' => 'The comment cannot be empty',
            'body.min' => 'The comment must have at least 3 characters',
            'body.max' => 'The comment must have at most 1000 characters',
        ];
    }
}
