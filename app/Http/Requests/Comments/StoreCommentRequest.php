<?php

namespace App\Http\Requests\Comments;

use App\DTOs\Requests\Comments\StoreCommentDTO;
use Illuminate\Foundation\Http\FormRequest;

class StoreCommentRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'post_id' => 'required|integer|exists:posts,id',
            'body' => 'required|string|max:1000',
        ];
    }

    public function run(StoreCommentRequest $request)
    {
        $dto = StoreCommentDTO::fromRequest($request);
        $this->commentService->createComment($dto);

        return redirect()->refresh()->with('success', 'Comentario criado com sucesso!');
    }
}
