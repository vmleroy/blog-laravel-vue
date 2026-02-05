<?php

namespace App\DTOs\Responses\Comments;

use Illuminate\Support\Facades\DB;

class CommentResponseDTO
{
    public function __construct(
        public int $id,
        public int $post_id,
        public int $user_id,
        public string $body,
        public ?string $author_name = null,
        public ?string $created_at = null,
        public ?string $updated_at = null,
    ) {
    }

    public static function fromModel($comment): self
    {
        $author = DB::connection('auth_db')
            ->table('users')
            ->where('id', $comment->user_id)
            ->first(['name']);

        return new self(
            id: $comment->id,
            post_id: $comment->post_id,
            user_id: $comment->user_id,
            body: $comment->body,
            author_name: $author?->name ?? 'Autor Desconhecido',
            created_at: $comment->created_at->toDateTimeString(),
            updated_at: $comment->updated_at->toDateTimeString(),
        );
    }
}
