<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Posts\StorePostRequest;
use App\Http\Requests\Posts\GetPostByIdRequest;
use App\Http\Requests\Posts\UpdatePostRequest;
use App\Http\Requests\Posts\DeletePostRequest;
use App\DTOs\Requests\Posts\StorePostRequestDTO;
use App\DTOs\Requests\Posts\GetPostRequestDTO;
use App\DTOs\Requests\Posts\UpdatePostRequestDTO;
use App\DTOs\Requests\Posts\DeletePostRequestDTO;
use App\DTOs\Responses\Posts\PostResponseDTO;
use App\Services\Posts\PostService;

class PostController extends Controller
{
    public function __construct(protected PostService $postService) {}

    // GET /api/v1/posts
    public function index()
    {
        $posts = $this->postService->getAllPosts();
        return response()->json(
            $posts->map(fn($post) => PostResponseDTO::fromModel($post))
        );
    }

    // POST /api/v1/posts
    public function store(StorePostRequest $request)
    {
        $dto = StorePostRequestDTO::fromRequest($request);
        $userId = $request->attributes->get('user')['id'];
        $post = $this->postService->createPost($dto, $userId);
        return response()->json(PostResponseDTO::fromModel($post), 201);
    }

    // GET /api/v1/posts/{id}
    public function show(GetPostByIdRequest $request, $id)
    {
        $dto = GetPostRequestDTO::fromRequest($request);
        $post = $this->postService->getPostById($dto);
        return response()->json(PostResponseDTO::fromModel($post));
    }

    // PUT /api/v1/posts/{id}
    public function update(UpdatePostRequest $request, $id)
    {
        $dto = UpdatePostRequestDTO::fromRequest($request);
        $post = $this->postService->updatePost($dto);
        return response()->json(PostResponseDTO::fromModel($post));
    }

    // DELETE /api/v1/posts/{id}
    public function destroy(DeletePostRequest $request, $id)
    {
        $dto = DeletePostRequestDTO::fromRequest($request);
        $this->postService->deletePost($dto);
        return response()->json(null, 204);
    }
}
