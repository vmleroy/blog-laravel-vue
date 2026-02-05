<?php

namespace App\Services\Posts;

use App\DTOs\Requests\Posts\StorePostRequestDTO;
use App\DTOs\Requests\Posts\GetPostRequestDTO;
use App\DTOs\Requests\Posts\UpdatePostRequestDTO;
use App\DTOs\Requests\Posts\DeletePostRequestDTO;
use App\Models\Posts\Post;
use App\Services\MessageQueue\ServiceMessenger;

class PostService
{
    public function getAllPosts()
    {
        $posts = Post::all();
        return $posts->map(function ($post) {
            $this->enrichPostWithAuthor($post);
            return $post;
        });
    }

    public function createPost(StorePostRequestDTO $data, int $userId): Post
    {
        $post = Post::create([
            'title' => $data->title,
            'content' => $data->content,
            'user_id' => $userId,
        ]);

        $this->enrichPostWithAuthor($post);
        return $post;
    }

    public function getPostById(GetPostRequestDTO $data): Post
    {
        $post = Post::findOrFail($data->id);
        $this->enrichPostWithAuthor($post);
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

        $this->enrichPostWithAuthor($post);
        return $post;
    }

    public function deletePost(DeletePostRequestDTO $data): void
    {
        $post = Post::findOrFail($data->id);
        $post->delete();
    }

    private function enrichPostWithAuthor($post)
    {
        // Use ServiceMessenger to get user info from Auth service
        $userInfo = ServiceMessenger::send('auth', 'getUserInfo', ['user_id' => $post->user_id]);

        // Add author_name as a dynamic attribute
        $post->author_name = $userInfo['name'];
    }
}
