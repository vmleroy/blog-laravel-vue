<?php

namespace App\Http\Controllers\Gateway;

use App\Http\Controllers\Controller;
use App\Http\Requests\Gateway\Comments\GatewayIndexCommentsRequest;
use App\Http\Requests\Gateway\Comments\GatewayShowCommentRequest;
use App\Http\Requests\Gateway\Comments\GatewayStoreCommentRequest;
use App\Http\Requests\Gateway\Comments\GatewayUpdateCommentRequest;
use App\Http\Requests\Gateway\Comments\GatewayDeleteCommentRequest;
use App\Services\Proxies\CommentsServiceProxy;
use Illuminate\Http\JsonResponse;

class CommentsGatewayController extends Controller
{
    public function __construct(
        private CommentsServiceProxy $commentsService
    ) {}

    /**
     * GET /gateway/comments?post_id={id}
     */
    public function index(GatewayIndexCommentsRequest $request): JsonResponse
    {
        try {
            $postId = $request->query('post_id');

            $comments = $this->commentsService->getComments(
                $postId ? (int) $postId : null
            );

            return response()->json($comments, 200);
        } catch (\Exception $e) {
            return response()->json([
                'error' => $e->getMessage(),
            ], $e->getCode() ?: 500);
        }
    }

    /**
     * GET /gateway/comments/{id}
     */
    public function show(GatewayShowCommentRequest $request, $id): JsonResponse
    {
        try {
            $comment = $this->commentsService->getCommentById((int) $id);
            return response()->json($comment, 200);
        } catch (\Exception $e) {
            return response()->json([
                'error' => $e->getMessage(),
            ], $e->getCode() ?: 404);
        }
    }

    /**
     * POST /gateway/comments
     */
    public function store(GatewayStoreCommentRequest $request): JsonResponse
    {
        try {
            $user = $request->attributes->get('user');

            $comment = $this->commentsService->createComment([
                'post_id' => $request->post_id,
                'body' => $request->body,
                'user_id' => $user['user_id'],
            ]);

            return response()->json($comment, 201);
        } catch (\Exception $e) {
            return response()->json([
                'error' => $e->getMessage(),
            ], $e->getCode() ?: 500);
        }
    }

    /**
     * PUT /gateway/comments/{id}
     */
    public function update(GatewayUpdateCommentRequest $request, $id): JsonResponse
    {
        try {
            $comment = $this->commentsService->updateComment((int) $id, [
                'body' => $request->body,
            ]);

            return response()->json($comment, 200);
        } catch (\Exception $e) {
            return response()->json([
                'error' => $e->getMessage(),
            ], $e->getCode() ?: 500);
        }
    }

    /**
     * DELETE /gateway/comments/{id}
     */
    public function destroy(GatewayDeleteCommentRequest $request, $id): JsonResponse
    {
        try {
            $this->commentsService->deleteComment((int) $id);
            return response()->json(null, 204);
        } catch (\Exception $e) {
            return response()->json([
                'error' => $e->getMessage(),
            ], $e->getCode() ?: 500);
        }
    }
}
