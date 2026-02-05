<?php

namespace App\DTOs\Responses\Comments;

class CommentResponseDTO
{
    public function __construct(
        public int $id,
        public int $post_id,
        public string $body,
        public ?string $created_at = null,
        public ?string $updated_at = null,
    ) {
    }

    public static function fromModel($comment): self
    {
        return new self(
            id: $comment->id,
            post_id: $comment->post_id,
            body: $comment->body,
            created_at: $comment->created_at->toDateTimeString(),
            updated_at: $comment->updated_at->toDateTimeString(),
        );
    }
}
