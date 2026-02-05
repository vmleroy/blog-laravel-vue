<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckPostOwnership
{
    public function handle(Request $request, Closure $next): Response
    {
        $postId = $request->route('id');
        $user = $request->attributes->get('user');

        // Admin can do anything
        if ($user && isset($user['role']) && $user['role'] === 'admin') {
            return $next($request);
        }

        // Check if user is the owner
        $post = \App\Models\Posts\Post::find($postId);

        if (!$post || $post->user_id !== $user['user_id']) {
            return response()->json([
                'error' => 'Você não tem permissão para realizar esta ação',
            ], Response::HTTP_FORBIDDEN);
        }

        return $next($request);
    }
}
