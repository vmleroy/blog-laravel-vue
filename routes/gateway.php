<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Gateway\AuthGatewayController;
use App\Http\Controllers\Gateway\PostsGatewayController;
use App\Http\Controllers\Gateway\CommentsGatewayController;
use App\Http\Controllers\Gateway\RbacGatewayController;

Route::prefix('gateway')->group(function () {
    Route::get('/health', function () {
        return response()->json([
            'service' => 'api-gateway',
            'status' => 'healthy',
            'timestamp' => now()->toIso8601String(),
        ]);
    });

    /*
    |--------------------------------------------------------------------------
    | Public Routes - No authentication required
    |--------------------------------------------------------------------------
    */
    Route::prefix('auth')->group(function () {
        Route::post('login', [AuthGatewayController::class, 'login']);
        Route::post('register', [AuthGatewayController::class, 'register']);
        Route::post('refresh', [AuthGatewayController::class, 'refresh']);
    });

    Route::prefix('posts')->group(function() {
        Route::get('/', [PostsGatewayController::class, 'index']);
        Route::get('/{id}', [PostsGatewayController::class, 'show']);
    });

    Route::prefix('comments')->group(function() {
        Route::get('/', [CommentsGatewayController::class, 'index']);
        Route::get('/{id}', [CommentsGatewayController::class, 'show']);
    });

    /*
    |--------------------------------------------------------------------------
    | Protected Routes - JWT authentication required
    |--------------------------------------------------------------------------
    */
    Route::middleware(['gateway.auth'])->group(function () {
        Route::post('auth/logout', [AuthGatewayController::class, 'logout']);

        Route::prefix('posts')->group(function() {
            Route::post('/', [PostsGatewayController::class, 'store']);
            Route::put('/{id}', [PostsGatewayController::class, 'update']);
            Route::delete('/{id}', [PostsGatewayController::class, 'destroy']);
        });

        Route::prefix('comments')->group(function() {
            Route::post('/', [CommentsGatewayController::class, 'store']);
            Route::put('/{id}', [CommentsGatewayController::class, 'update']);
            Route::delete('/{id}', [CommentsGatewayController::class, 'destroy']);
        });

        Route::prefix('rbac')->group(function () {
            Route::post('roles', [RbacGatewayController::class, 'createRole']);
            Route::post('roles/assign', [RbacGatewayController::class, 'assignRole']);
            Route::get('users/{userId}/role', [RbacGatewayController::class, 'getUserRole']);
            Route::post('check-access', [RbacGatewayController::class, 'checkAccess']);
        });
    });
});
