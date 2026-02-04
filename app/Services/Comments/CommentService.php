<?php

namespace App\Services\Comments;

use App\DTOs\Requests\Comments\StoreCommentDTO;
use App\DTOs\Requests\Comments\GetCommentByPostIdDTO;
use App\Models\Comments\Comment;

class CommentService
{
    public function getCommentsByPost(GetCommentByPostIdDTO $data): array
    {
        return Comment::where('post_id', $data->post_id)
            ->get()
            ->map(fn(Comment $comment) => $comment)
            ->toArray();
    }

    public function createComment(StoreCommentDTO $data): Comment
    {
        $comment = Comment::create([
            'post_id' => $data->post_id,
            'body' => $data->body
        ]);

        return $comment;
    }
}
