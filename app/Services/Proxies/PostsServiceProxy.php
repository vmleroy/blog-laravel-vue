<?php

namespace App\Services\Proxies;

use App\Services\Posts\PostService;
use App\DTOs\Requests\Posts\StorePostRequestDTO;
use App\DTOs\Requests\Posts\GetPostRequestDTO;
use App\DTOs\Requests\Posts\UpdatePostRequestDTO;
use App\DTOs\Requests\Posts\DeletePostRequestDTO;

class PostsServiceProxy
{
    public function __construct(
        private PostService $postService = new PostService()
    ) {}

    /**
     * Get all posts
     */
    public function getAllPosts(): array
    {
        return $this->postService->getAllPosts()->toArray();
    }

    /**
     * Get a post by ID
     */
    public function getPostById(int $id): array
    {
        $dto = new GetPostRequestDTO(id: $id);
        return $this->postService->getPostById($dto)->toArray();
    }

    /**
     * Create a new post
     */
    public function createPost(array $data): array
    {
        $dto = new StorePostRequestDTO(
            title: $data['title'],
            content: $data['content']
        );
        return $this->postService->createPost($dto, $data['user_id'])->toArray();
    }

    /**
     * Update a post
     */
    public function updatePost(int $id, array $data): array
    {
        $dto = new UpdatePostRequestDTO(
            id: $id,
            title: $data['title'] ?? null,
            content: $data['content'] ?? null
        );
        return $this->postService->updatePost($dto)->toArray();
    }

    /**
     * Delete a post
     */
    public function deletePost(int $id): void
    {
        $dto = new DeletePostRequestDTO(id: $id);
        $this->postService->deletePost($dto);
    }

    /**
     * Check if a post exists
     */
    public function checkPostExists(int $id): bool
    {
        try {
            $dto = new GetPostRequestDTO(id: $id);
            $this->postService->getPostById($dto);
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }
}
