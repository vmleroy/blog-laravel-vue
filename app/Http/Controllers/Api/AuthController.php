<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;
use App\Http\Requests\Auth\RefreshTokenRequest;
use App\Http\Requests\Auth\LogoutRequest;
use App\DTOs\Requests\Auth\LoginRequestDTO;
use App\DTOs\Requests\Auth\RegisterRequestDTO;
use App\DTOs\Requests\Auth\RefreshTokenRequestDTO;
use App\DTOs\Requests\Auth\LogoutRequestDTO;
use App\DTOs\Responses\Auth\AuthResponseDTO;
use App\Services\Auth\AuthService;
use Illuminate\Http\JsonResponse;

class AuthController extends Controller
{
    public function __construct(protected AuthService $authService) {}

    /**
     * POST /api/v1/auth/login
     * Autenticar usuário
     */
    public function login(LoginRequest $request): JsonResponse
    {
        try {
            $dto = LoginRequestDTO::fromRequest($request);
            $data = $this->authService->login($dto);
            $response = AuthResponseDTO::fromArray($data);

            return response()->json($response, 200);
        } catch (\Exception $e) {
            return response()->json([
                'error' => $e->getMessage(),
            ], $e->getCode() ?: 400);
        }
    }

    /**
     * POST /api/v1/auth/register
     * Registrar novo usuário
     */
    public function register(RegisterRequest $request): JsonResponse
    {
        try {
            $dto = RegisterRequestDTO::fromRequest($request);
            $data = $this->authService->register($dto);
            $response = AuthResponseDTO::fromArray($data);

            return response()->json($response, 201);
        } catch (\Exception $e) {
            return response()->json([
                'error' => $e->getMessage(),
            ], $e->getCode() ?: 400);
        }
    }

    /**
     * POST /api/v1/auth/refresh
     * Renovar token
     */
    public function refreshToken(RefreshTokenRequest $request): JsonResponse
    {
        try {
            $dto = RefreshTokenRequestDTO::fromRequest($request);
            $data = $this->authService->refreshToken($dto);
            $response = AuthResponseDTO::fromArray($data);

            return response()->json($response, 200);
        } catch (\Exception $e) {
            return response()->json([
                'error' => $e->getMessage(),
            ], $e->getCode() ?: 400);
        }
    }

    /**
     * POST /api/v1/auth/logout
     * Logout do usuário
     */
    public function logout(LogoutRequest $request): JsonResponse
    {
        try {
            $dto = LogoutRequestDTO::fromRequest($request);
            $this->authService->logout($dto);

            return response()->json([
                'message' => 'Logout realizado com sucesso',
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'error' => $e->getMessage(),
            ], $e->getCode() ?: 400);
        }
    }
}
