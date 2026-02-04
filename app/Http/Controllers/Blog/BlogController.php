<?php

namespace App\Http\Controllers\Blog;

use App\Http\Controllers\Controller;
use App\Services\Posts\PostService;
use App\Services\Comments\CommentService;
use Illuminate\Http\Request;

class BlogController extends Controller
{
    protected $postService;
    protected $commentService;

    public function __construct(PostService $postService, CommentService $commentService)
    {
        $this->postService = $postService;
        $this->commentService = $commentService;
    }

    public function index()
    {
        $posts = $this->postService->getAllPosts();
        return view('blog.index', compact('posts'));
    }

    public function show($id)
    {
        $post = $this->postService->getPostById($id);

        // Comunicação entre "serviços":
        // O Controller pega o dado de um e passa para o outro.
        $comments = $this->commentService->getCommentsByPost($id);

        return view('blog.show', compact('post', 'comments'));
    }
}
