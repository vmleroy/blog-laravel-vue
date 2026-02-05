<?php

namespace App\Services\Comments;

use App\DTOs\Requests\Comments\StoreCommentDTO;
use App\DTOs\Requests\Comments\GetCommentByPostIdDTO;
use App\DTOs\Requests\Comments\GetCommentRequestDTO;
use App\DTOs\Requests\Comments\UpdateCommentRequestDTO;
use App\DTOs\Requests\Comments\DeleteCommentRequestDTO;
use App\Models\Comments\Comment;
use App\Services\MessageQueue\ServiceMessenger;

class CommentService
{
    public function getAllComments()
    {
        $comments = Comment::all();
        return $comments->map(function ($comment) {
            $this->enrichCommentWithAuthor($comment);
            return $comment;
        });
    }

    public function getCommentsByPost(GetCommentByPostIdDTO $data)
    {
        $comments = Comment::where('post_id', $data->post_id)->get();
        return $comments->map(function ($comment) {
            $this->enrichCommentWithAuthor($comment);
            return $comment;
        });
    }

    public function createComment(StoreCommentDTO $data, int $userId): Comment
    {
        $comment = Comment::create([
            'post_id' => $data->post_id,
            'body' => $data->body,
            'user_id' => $userId,
        ]);

        $this->enrichCommentWithAuthor($comment);
        return $comment;
    }

    public function getCommentById(GetCommentRequestDTO $data): Comment
    {
        $comment = Comment::findOrFail($data->id);
        $this->enrichCommentWithAuthor($comment);
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

        $this->enrichCommentWithAuthor($comment);
        return $comment;
    }

    public function deleteComment(DeleteCommentRequestDTO $data): void
    {
        $comment = Comment::findOrFail($data->id);
        $comment->delete();
    }

    private function enrichCommentWithAuthor($comment)
    {
        $userInfo = ServiceMessenger::send('auth', 'getUserInfo', ['user_id' => $comment->user_id]);
        $comment->author_name = $userInfo['name'];
    }
}
