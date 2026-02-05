<?php

namespace App\Http\Requests\Posts;

use App\DTOs\Requests\Posts\StorePostRequestDTO;
use Illuminate\Foundation\Http\FormRequest;

class StorePostRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'title' => 'required|string|max:255',
            'content' => 'required|string|min:10',
        ];
    }

    public function run(StorePostRequest $request)
    {
        // 1. O Request valida os dados
        // 2. O DTO transforma o request em um objeto tipado
        $dto = StorePostRequestDTO::fromRequest($request);

        // 3. O Service processa o DTO
        $this->postService->createPost($dto);

        return redirect()->back()->with('success', 'Post criado com sucesso!');
    }
}
