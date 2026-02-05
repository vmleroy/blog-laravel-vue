<?php

namespace App\Services\Comments;

use App\DTOs\Requests\Comments\StoreCommentDTO;
use App\DTOs\Requests\Comments\GetCommentByPostIdDTO;
use App\DTOs\Requests\Comments\GetCommentRequestDTO;
use App\DTOs\Requests\Comments\UpdateCommentRequestDTO;
use App\DTOs\Requests\Comments\DeleteCommentRequestDTO;
use App\Models\Comments\Comment;

class CommentService
{
    public function getCommentsByPost(GetCommentByPostIdDTO $data)
    {
        return Comment::where('post_id', $data->post_id)->get();
    }

    public function createComment(StoreCommentDTO $data): Comment
    {
        $comment = Comment::create([
            'post_id' => $data->post_id,
            'body' => $data->body
        ]);

        return $comment;
    }

    public function getCommentById(GetCommentRequestDTO $data): Comment
    {
        $comment = Comment::findOrFail($data->id);
        return $comment;
    }

    public function updateComment(UpdateCommentRequestDTO $data): Comment
    {
        $comment = Comment::findOrFail($data->id);

        $attributes = array_filter([
            'body' => $data->body,
        ], fn($value) => $value !== null);

        $comment->fill($attributes);
        $comment->save();

        return $comment;
    }

    public function deleteComment(DeleteCommentRequestDTO $data): void
    {
        $comment = Comment::findOrFail($data->id);
        $comment->delete();
    }
}
