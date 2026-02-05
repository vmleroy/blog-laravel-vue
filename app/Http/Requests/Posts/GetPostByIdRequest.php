<?php

namespace App\Http\Requests\Posts;

use App\DTOs\Requests\Posts\GetPostRequestDTO;
use App\DTOs\Responses\Posts\PostResponseDTO;
use Illuminate\Foundation\Http\FormRequest;

class GetPostByIdRequest extends FormRequest
{
    protected function prepareForValidation(): void
    {
        $this->merge(['id' => $this->route('id')]);
    }

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
            'id' => 'required|integer|min:1|exists:posts_db.posts,id',
        ];
    }

    public function run(): PostResponseDTO
    {
        $dto = GetPostRequestDTO::fromRequest($this->route('id'));
        $post = $this->postService->getPostById($dto);

        return PostResponseDTO::fromModel($post);
    }
}
