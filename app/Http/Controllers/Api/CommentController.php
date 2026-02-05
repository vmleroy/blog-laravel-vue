<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Comments\GetCommentByPostIdRequest;
use App\Http\Requests\Comments\StoreCommentRequest;
use App\Http\Requests\Comments\GetCommentRequest;
use App\Http\Requests\Comments\UpdateCommentRequest;
use App\Http\Requests\Comments\DeleteCommentRequest;
use App\DTOs\Requests\Comments\StoreCommentDTO;
use App\DTOs\Requests\Comments\GetCommentByPostIdDTO;
use App\DTOs\Requests\Comments\GetCommentRequestDTO;
use App\DTOs\Requests\Comments\UpdateCommentRequestDTO;
use App\DTOs\Requests\Comments\DeleteCommentRequestDTO;
use App\DTOs\Responses\Comments\CommentResponseDTO;
use App\Services\Comments\CommentService;

class CommentController extends Controller
{
    public function __construct(protected CommentService $commentService) {}

    // GET /api/v1/posts/{postId}/comments
    public function index(GetCommentByPostIdRequest $request, $postId)
    {
        $dto = GetCommentByPostIdDTO::fromRequest($request);
        $comments = $this->commentService->getCommentsByPost($dto);
        return response()->json(
            $comments->map(fn($comment) => CommentResponseDTO::fromModel($comment))
        );
    }

    // POST /api/v1/posts/{postId}/comments
    public function store(StoreCommentRequest $request, $postId)
    {
        $dto = StoreCommentDTO::fromRequest($request);
        $comment = $this->commentService->createComment($dto);
        return response()->json(CommentResponseDTO::fromModel($comment), 201);
    }

    // GET /api/v1/comments/{id}
    public function show(GetCommentRequest $request, $id)
    {
        $dto = GetCommentRequestDTO::fromRequest($request);
        $comment = $this->commentService->getCommentById($dto);
        return response()->json(CommentResponseDTO::fromModel($comment));
    }

    // PUT /api/v1/comments/{id}
    public function update(UpdateCommentRequest $request, $id)
    {
        $dto = UpdateCommentRequestDTO::fromRequest($request);
        $comment = $this->commentService->updateComment($dto);
        return response()->json(CommentResponseDTO::fromModel($comment));
    }

    // DELETE /api/v1/comments/{id}
    public function destroy(DeleteCommentRequest $request, $id)
    {
        $dto = DeleteCommentRequestDTO::fromRequest($request);
        $this->commentService->deleteComment($dto);
        return response()->json(null, 204);
    }
}
