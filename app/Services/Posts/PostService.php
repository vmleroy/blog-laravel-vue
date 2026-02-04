<?php

namespace App\Services\Posts;

use App\DTOs\Requests\Posts\StorePostRequestDTO;
use App\DTOs\Requests\Posts\GetPostRequestDTO;
use App\Models\Posts\Post;

class PostService
{
    public function getAllPosts(): array
    {
        return Post::all()->map(fn($post) => $post)->toArray();
    }

    public function createPost(StorePostRequestDTO $data): Post
    {
        $post = Post::create([
            'title' => $data->title,
            'content' => $data->content,
        ]);

        return $post;
    }

    public function getPostById(GetPostRequestDTO $data): Post
    {
        $post = Post::findOrFail($data->id);
        return $post;
    }
}
