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
            'post_id.required' => 'The post ID is required',
            'post_id.integer' => 'The post ID must be an integer',
            'post_id.min' => 'The post ID must be greater than 0',
            'body.required' => 'The comment cannot be empty',
            'body.min' => 'The comment must have at least 3 characters',
            'body.max' => 'The comment must have at most 1000 characters',
        ];
    }
}
