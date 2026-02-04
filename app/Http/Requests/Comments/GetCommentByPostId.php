<?php

namespace App\Http\Requests\Comments;

use App\DTOs\Requests\Comments\GetCommentByPostIdDTO;
use App\DTOs\Responses\Comments\CommentResponseDTO;
use Illuminate\Foundation\Http\FormRequest;

class GetCommentByPostId extends FormRequest
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
        ];
    }

    public function run(): CommentResponseDTO
    {
        $dto = GetCommentByPostIdDTO::fromRequest($this->route('post_id'));
        $comment = $this->commentService->getCommentByPostId($dto);

        return CommentResponseDTO::fromModel($comment);
    }
}
