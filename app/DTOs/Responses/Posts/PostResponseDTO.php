<?php

namespace App\DTOs\Responses\Posts;

use App\Models\Posts\Post;

class PostResponseDTO
{
    public function __construct(
        public int $id,
        public string $title,
        public ?string $content = null,
        public ?string $createdAt = null,
        public ?string $updatedAt = null
    ) {
    }

    public static function fromModel(Post $post): self
    {
        return new self(
            id: $post->id,
            title: $post->title,
            content: $post->content,
            createdAt: $post->created_at->toDateTimeString(),
            updatedAt: $post->updated_at->toDateTimeString()
        );
    }
}
