<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Services\Auth\AuthService;
use App\Services\RoleBasedAccess\RoleBasedAccessService;

class AuthenticateWithJWT
{
    public function __construct(
        private AuthService $authService,
        private RoleBasedAccessService $rbacService
    ) {
    }

    public function handle(Request $request, Closure $next): Response
    {
        $token = $request->bearerToken();

        if (!$token) {
            return response()->json([
                'error' => 'Token não fornecido',
            ], Response::HTTP_UNAUTHORIZED);
        }

        $validated = $this->authService->validateToken($token);

        if (!$validated) {
            return response()->json([
                'error' => 'Token inválido ou expirado',
            ], Response::HTTP_UNAUTHORIZED);
        }

        // Obter papel do usuário
        $role = $this->rbacService->getUserRole($validated['user_id']);
        $validated['role'] = $role ? $role->name : null;

        // Adicionar informações do usuário ao request
        $request->attributes->add(['user' => $validated]);

        return $next($request);
    }
}
