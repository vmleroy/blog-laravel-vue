<?php

namespace App\Http\Requests\Gateway\Posts;

use Illuminate\Foundation\Http\FormRequest;

class GatewayShowPostRequest extends FormRequest
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
            'id.required' => 'The post ID is required',
            'id.integer' => 'The post ID must be an integer',
            'id.min' => 'The post ID must be greater than 0',
        ];
    }
}
