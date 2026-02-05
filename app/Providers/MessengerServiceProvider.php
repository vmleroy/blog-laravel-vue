<?php

namespace App\Providers;

use App\Services\MessageQueue\ServiceMessenger;
use App\Services\Auth\AuthService;
use App\Services\Posts\PostService;
use App\Services\Comments\CommentService;
use App\Services\RoleBasedAccess\RoleBasedAccessService;
use Illuminate\Support\ServiceProvider;

class MessengerServiceProvider extends ServiceProvider
{
    /**
     * Register the service messenger and all microservices
     * This allows services to call each other using ServiceMessenger::send()
     */
    public function register(): void
    {
        // Register Auth service
        ServiceMessenger::register('auth', function (string $action, array $params) {
            $service = new AuthService();
            return match ($action) {
                'getUserInfo' => $service->getUserInfo($params['user_id']),
                default => throw new \Exception("Unknown auth action: {$action}", 400),
            };
        });

        // Register Posts service
        ServiceMessenger::register('posts', function (string $action, array $params) {
            $service = new PostService();
            return match ($action) {
                default => throw new \Exception("Unknown posts action: {$action}", 400),
            };
        });

        // Register Comments service
        ServiceMessenger::register('comments', function (string $action, array $params) {
            $service = new CommentService();
            return match ($action) {
                default => throw new \Exception("Unknown comments action: {$action}", 400),
            };
        });

        // Register RBAC service
        ServiceMessenger::register('rbac', function (string $action, array $params) {
            $service = new RoleBasedAccessService();
            return match ($action) {
                'getUserRole' => $service->getUserRole($params['user_id'])?->toArray(),
                default => throw new \Exception("Unknown rbac action: {$action}", 400),
            };
        });
    }

    /**
     * Bootstrap any services
     */
    public function boot(): void
    {
        //
    }
}
