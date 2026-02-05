<?php

namespace App\DTOs\Responses\Posts;

use App\Models\Posts\Post;
use Illuminate\Support\Facades\DB;

class PostResponseDTO
{
    public function __construct(
        public int $id,
        public int $user_id,
        public string $title,
        public ?string $content = null,
        public ?string $author_name = null,
        public ?string $createdAt = null,
        public ?string $updatedAt = null
    ) {
    }

    public static function fromModel(Post $post): self
    {
        $author = DB::connection('auth_db')
            ->table('users')
            ->where('id', $post->user_id)
            ->first(['name']);

        return new self(
            id: $post->id,
            user_id: $post->user_id,
            title: $post->title,
            content: $post->content,
            author_name: $author?->name ?? 'Autor Desconhecido',
            createdAt: $post->created_at->toDateTimeString(),
            updatedAt: $post->updated_at->toDateTimeString()
        );
    }
}
