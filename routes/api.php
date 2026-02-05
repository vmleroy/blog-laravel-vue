<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\PostController;
use App\Http\Controllers\Api\CommentController;

// Health Check para microsserviços
Route::get('/health', function () {
    return response()->json([
        'service' => 'blog-service',
        'status' => 'healthy',
        'timestamp' => now()->toIso8601String(),
    ]);
});

// API v1 - Posts Service
Route::prefix('v1')->group(function () {

    // Posts Endpoints
    Route::prefix('posts')->group(function () {
        Route::get('/', [PostController::class, 'index']);
        Route::post('/', [PostController::class, 'store']);
        Route::get('/{id}', [PostController::class, 'show']);
        Route::put('/{id}', [PostController::class, 'update']);
        Route::delete('/{id}', [PostController::class, 'destroy']);

        // Comments relacionados a Posts
        Route::prefix('{postId}/comments')->group(function () {
            Route::get('/', [CommentController::class, 'index']);
            Route::post('/', [CommentController::class, 'store']);
        });
    });

    // Comments Service (endpoints independentes)
    Route::prefix('comments')->group(function () {
        Route::get('/', [CommentController::class, 'index']);
        Route::get('/{id}', [CommentController::class, 'show']);
        Route::put('/{id}', [CommentController::class, 'update']);
        Route::delete('/{id}', [CommentController::class, 'destroy']);
    });
});

// Rota de autenticação (para futuro)
Route::prefix('auth')->group(function () {
    Route::post('/login', function () {
        return response()->json(['message' => 'Not implemented yet'], 501);
    });
    Route::post('/logout', function () {
        return response()->json(['message' => 'Not implemented yet'], 501);
    });
});

