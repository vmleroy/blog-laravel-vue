<?php

namespace App\Http\Requests\Gateway\Posts;

use Illuminate\Foundation\Http\FormRequest;

class GatewayUpdatePostRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title' => 'sometimes|required|string|min:5|max:255',
            'content' => 'sometimes|required|string|min:10',
        ];
    }

    public function messages(): array
    {
        return [
            'title.required' => 'The title is required',
            'title.min' => 'The title must have at least 5 characters',
            'title.max' => 'The title must have at most 255 characters',
            'content.required' => 'The content is required',
            'content.min' => 'The content must have at least 10 characters',
        ];
    }
}
