<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes - Gateway Only
|--------------------------------------------------------------------------
| All API requests must go through the Gateway for proper authentication
| and authorization. Direct service routes are not exposed to clients.
*/

require __DIR__.'/gateway.php';

// Health check endpoint
Route::get('/health', function () {
    return response()->json([
        'service' => 'blog-api',
        'status' => 'healthy',
        'timestamp' => now()->toIso8601String(),
    ]);
});

