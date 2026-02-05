<?php

namespace App\Http\Controllers\Gateway;

use App\Http\Controllers\Controller;
use App\Http\Requests\Gateway\Posts\GatewayShowPostRequest;
use App\Http\Requests\Gateway\Posts\GatewayStorePostRequest;
use App\Http\Requests\Gateway\Posts\GatewayUpdatePostRequest;
use App\Http\Requests\Gateway\Posts\GatewayDeletePostRequest;
use App\Services\Proxies\PostsServiceProxy;
use Illuminate\Http\JsonResponse;

class PostsGatewayController extends Controller
{
    public function __construct(
        private PostsServiceProxy $postsService
    ) {}

    /**
     * GET /gateway/posts
     */
    public function index(): JsonResponse
    {
        try {
            $posts = $this->postsService->getAllPosts();
            return response()->json($posts, 200);
        } catch (\Exception $e) {
            return response()->json([
                'error' => $e->getMessage(),
            ], $e->getCode() ?: 500);
        }
    }

    /**
     * GET /gateway/posts/{id}
     */
    public function show(GatewayShowPostRequest $request, $id): JsonResponse
    {
        try {
            $post = $this->postsService->getPostById((int) $id);
            return response()->json($post, 200);
        } catch (\Exception $e) {
            return response()->json([
                'error' => $e->getMessage(),
            ], $e->getCode() ?: 404);
        }
    }

    /**
     * POST /gateway/posts
     */
    public function store(GatewayStorePostRequest $request): JsonResponse
    {
        try {
            $user = $request->attributes->get('user');

            $post = $this->postsService->createPost([
                'title' => $request->input('title'),
                'content' => $request->input('content'),
                'user_id' => $user['user_id'],
            ]);

            return response()->json($post, 201);
        } catch (\Exception $e) {
            return response()->json([
                'error' => $e->getMessage(),
            ], $e->getCode() ?: 500);
        }
    }

    /**
     * PUT /gateway/posts/{id}
     */
    public function update(GatewayUpdatePostRequest $request, $id): JsonResponse
    {
        try {
            $post = $this->postsService->updatePost((int) $id, [
                'title' => $request->input('title'),
                'content' => $request->input('content'),
            ]);

            return response()->json($post, 200);
        } catch (\Exception $e) {
            return response()->json([
                'error' => $e->getMessage(),
            ], $e->getCode() ?: 500);
        }
    }

    /**
     * DELETE /gateway/posts/{id}
     */
    public function destroy(GatewayDeletePostRequest $request, $id): JsonResponse
    {
        try {
            $this->postsService->deletePost((int) $id);
            return response()->json(null, 204);
        } catch (\Exception $e) {
            return response()->json([
                'error' => $e->getMessage(),
            ], $e->getCode() ?: 500);
        }
    }
}
