<?php

namespace App\Services\Posts;

use App\DTOs\Requests\Posts\StorePostRequestDTO;
use App\DTOs\Requests\Posts\GetPostRequestDTO;
use App\DTOs\Requests\Posts\UpdatePostRequestDTO;
use App\DTOs\Requests\Posts\DeletePostRequestDTO;
use App\Models\Posts\Post;

class PostService
{
    public function getAllPosts()
    {
        return Post::all();
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

    public function updatePost(UpdatePostRequestDTO $data): Post
    {
        $post = Post::findOrFail($data->id);

        $attributes = array_filter([
            'title' => $data->title,
            'content' => $data->content,
        ], fn($value) => $value !== null);

        $post->fill($attributes);
        $post->save();

        return $post;
    }

    public function deletePost(DeletePostRequestDTO $data): void
    {
        $post = Post::findOrFail($data->id);
        $post->delete();
    }
}
