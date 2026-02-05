<?php

namespace App\Http\Controllers\Gateway;

use App\Http\Controllers\Controller;
use App\Http\Requests\Gateway\Auth\GatewayLoginRequest;
use App\Http\Requests\Gateway\Auth\GatewayRegisterRequest;
use App\Http\Requests\Gateway\Auth\GatewayRefreshTokenRequest;
use App\Http\Requests\Gateway\Auth\GatewayLogoutRequest;
use App\Services\Proxies\AuthServiceProxy;
use Illuminate\Http\JsonResponse;

class AuthGatewayController extends Controller
{
    public function __construct(
        private AuthServiceProxy $authService
    ) {}

    /**
     * POST /gateway/auth/login
     */
    public function login(GatewayLoginRequest $request): JsonResponse
    {
        try {
            $response = $this->authService->login(
                $request->email,
                $request->password
            );

            return response()->json($response, 200);
        } catch (\Exception $e) {
            return response()->json([
                'error' => $e->getMessage(),
            ], $e->getCode() ?: 400);
        }
    }

    /**
     * POST /gateway/auth/register
     */
    public function register(GatewayRegisterRequest $request): JsonResponse
    {
        try {
            $response = $this->authService->register(
                $request->name,
                $request->email,
                $request->password
            );

            return response()->json($response, 201);
        } catch (\Exception $e) {
            return response()->json([
                'error' => $e->getMessage(),
            ], $e->getCode() ?: 400);
        }
    }

    /**
     * POST /gateway/auth/refresh
     */
    public function refresh(GatewayRefreshTokenRequest $request): JsonResponse
    {
        try {
            $response = $this->authService->refreshToken($request->token);

            return response()->json($response, 200);
        } catch (\Exception $e) {
            return response()->json([
                'error' => $e->getMessage(),
            ], $e->getCode() ?: 400);
        }
    }

    /**
     * POST /gateway/auth/logout
     */
    public function logout(GatewayLogoutRequest $request): JsonResponse
    {
        try {
            $token = $request->bearerToken();

            if (!$token) {
                return response()->json([
                    'error' => 'Token not provided',
                ], 401);
            }

            $response = $this->authService->logout($token);

            return response()->json($response, 200);
        } catch (\Exception $e) {
            return response()->json([
                'error' => $e->getMessage(),
            ], $e->getCode() ?: 400);
        }
    }
}
