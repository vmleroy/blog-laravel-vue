<?php

namespace App\Services\Proxies;

use App\Services\Auth\AuthService;
use App\DTOs\Requests\Auth\LoginRequestDTO;
use App\DTOs\Requests\Auth\RegisterRequestDTO;
use App\DTOs\Requests\Auth\RefreshTokenRequestDTO;
use App\DTOs\Requests\Auth\LogoutRequestDTO;

class AuthServiceProxy
{
    public function __construct(
        private AuthService $authService = new AuthService()
    ) {}

    /**
     * Authenticate user with email and password
     */
    public function login(string $email, string $password): array
    {
        $dto = new LoginRequestDTO(email: $email, password: $password);
        return $this->authService->login($dto);
    }

    /**
     * Register a new user
     */
    public function register(string $name, string $email, string $password): array
    {
        $dto = new RegisterRequestDTO(name: $name, email: $email, password: $password);
        return $this->authService->register($dto);
    }

    /**
     * Refresh authentication token
     */
    public function refreshToken(string $token): array
    {
        $dto = new RefreshTokenRequestDTO(token: $token);
        return $this->authService->refreshToken($dto);
    }

    /**
     * Logout user
     */
    public function logout(string $token): array
    {
        $dto = new LogoutRequestDTO(token: $token);
        $this->authService->logout($dto);
        return ['success' => true, 'message' => 'Logged out successfully'];
    }
}
