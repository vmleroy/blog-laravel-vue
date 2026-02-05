<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckCommentOwnership
{
    public function handle(Request $request, Closure $next): Response
    {
        $commentId = $request->route('id');
        $user = $request->attributes->get('user');

        // Admin can do anything
        if ($user && isset($user['role']) && $user['role'] === 'admin') {
            return $next($request);
        }

        // Check if user is the owner
        $comment = \App\Models\Comments\Comment::find($commentId);

        if (!$comment || $comment->user_id !== $user['id']) {
            return response()->json([
                'error' => 'Você não tem permissão para realizar esta ação',
            ], Response::HTTP_FORBIDDEN);
        }

        return $next($request);
    }
}
