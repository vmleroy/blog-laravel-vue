<?php

namespace App\Services\Proxies;

use App\Services\Comments\CommentService;
use App\DTOs\Requests\Comments\StoreCommentDTO;
use App\DTOs\Requests\Comments\GetCommentRequestDTO;
use App\DTOs\Requests\Comments\GetCommentByPostIdDTO;
use App\DTOs\Requests\Comments\UpdateCommentRequestDTO;
use App\DTOs\Requests\Comments\DeleteCommentRequestDTO;

class CommentsServiceProxy
{
    public function __construct(
        private CommentService $commentService = new CommentService()
    ) {}

    /**
     * Get all comments
     */
    public function getComments(?int $postId = null): array
    {
        if ($postId) {
            return $this->getCommentsByPost($postId);
        }
        return $this->commentService->getAllComments()->toArray();
    }

    /**
     * Get comments by post ID
     */
    public function getCommentsByPost(int $postId): array
    {
        $dto = new GetCommentByPostIdDTO(post_id: $postId);
        return $this->commentService->getCommentsByPost($dto)->toArray();
    }

    /**
     * Get a comment by ID
     */
    public function getCommentById(int $id): array
    {
        $dto = new GetCommentRequestDTO(id: $id);
        return $this->commentService->getCommentById($dto)->toArray();
    }

    /**
     * Create a new comment
     */
    public function createComment(array $data): array
    {
        $dto = new StoreCommentDTO(
            post_id: $data['post_id'],
            body: $data['body']
        );
        return $this->commentService->createComment($dto, $data['user_id'])->toArray();
    }

    /**
     * Update a comment
     */
    public function updateComment(int $id, array $data): array
    {
        $dto = new UpdateCommentRequestDTO(
            id: $id,
            body: $data['body'] ?? null
        );
        return $this->commentService->updateComment($dto)->toArray();
    }

    /**
     * Delete a comment
     */
    public function deleteComment(int $id): void
    {
        $dto = new DeleteCommentRequestDTO(id: $id);
        $this->commentService->deleteComment($dto);
    }
}
