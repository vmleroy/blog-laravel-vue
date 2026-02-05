<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\PostController;
use App\Http\Controllers\Api\CommentController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\RoleBasedAccessController;

// Health Check para microsserviços
Route::get('/health', function () {
    return response()->json([
        'service' => 'blog-service',
        'status' => 'healthy',
        'timestamp' => now()->toIso8601String(),
    ]);
});

// API v1 - Auth Service (Public - sem autenticação)
Route::prefix('v1/auth')->group(function () {
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/refresh', [AuthController::class, 'refreshToken']);
    Route::post('/logout', [AuthController::class, 'logout']);
});

// API v1 - Public Posts/Comments (Anyone can view)
Route::prefix('v1')->group(function () {
    // Posts - apenas leitura pública
    Route::prefix('posts')->group(function () {
        Route::get('/', [PostController::class, 'index']);
        Route::get('/{id}', [PostController::class, 'show']);

        // Comments relacionados a Posts - apenas leitura pública
        Route::prefix('{postId}/comments')->group(function () {
            Route::get('/', [CommentController::class, 'index']);
        });
    });

    // Comments - apenas leitura pública
    Route::prefix('comments')->group(function () {
        Route::get('/', [CommentController::class, 'index']);
        Route::get('/{id}', [CommentController::class, 'show']);
    });
});

// API v1 - Protected Routes (requer autenticação)
Route::prefix('v1')->middleware(\App\Http\Middleware\AuthenticateWithJWT::class)->group(function () {
    // Posts - criar e atualizar/deletar próprios posts
    Route::prefix('posts')->group(function () {
        Route::post('/', [PostController::class, 'store']);
        Route::put('/{id}', [PostController::class, 'update'])->middleware(\App\Http\Middleware\CheckPostOwnership::class);
        Route::delete('/{id}', [PostController::class, 'destroy'])->middleware(\App\Http\Middleware\CheckPostOwnership::class);

        // Comments - criar comentários
        Route::prefix('{postId}/comments')->group(function () {
            Route::post('/', [CommentController::class, 'store']);
        });
    });

    // Comments - atualizar/deletar próprios comentários
    Route::prefix('comments')->group(function () {
        Route::put('/{id}', [CommentController::class, 'update'])->middleware(\App\Http\Middleware\CheckCommentOwnership::class);
        Route::delete('/{id}', [CommentController::class, 'destroy'])->middleware(\App\Http\Middleware\CheckCommentOwnership::class);
    });

    // Role-Based Access Control Service
    Route::prefix('rbac')->group(function () {
        Route::post('/roles', [RoleBasedAccessController::class, 'createRole']);
        Route::post('/users/{userId}/role', [RoleBasedAccessController::class, 'assignRole']);
        Route::get('/users/{userId}/role', [RoleBasedAccessController::class, 'getUserRole']);
        Route::post('/check-access', [RoleBasedAccessController::class, 'checkAccess']);
        Route::post('/content-access', [RoleBasedAccessController::class, 'setContentAccess']);
        Route::post('/check-content-access', [RoleBasedAccessController::class, 'checkContentAccess']);
    });
});

