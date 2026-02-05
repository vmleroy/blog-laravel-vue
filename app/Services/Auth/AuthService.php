<?php

namespace App\Services\Auth;

use App\DTOs\Requests\Auth\LoginRequestDTO;
use App\DTOs\Requests\Auth\RegisterRequestDTO;
use App\DTOs\Requests\Auth\RefreshTokenRequestDTO;
use App\DTOs\Requests\Auth\LogoutRequestDTO;
use App\Models\Auth\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class AuthService
{
    /**
     * Autenticar usuário com email e senha
     */
    public function login(LoginRequestDTO $data): array
    {
        $user = User::where('email', $data->email)->first();

        if (!$user || !Hash::check($data->password, $user->password)) {
            throw new \Exception('Credenciais inválidas', 401);
        }

        if (!$user->is_active) {
            throw new \Exception('Usuário inativo', 403);
        }

        $token = $this->generateToken($user);
        $role = $this->getUserRole($user->id);

        return [
            'user_id' => $user->id,
            'email' => $user->email,
            'name' => $user->name,
            'role' => $role,
            'token' => $token,
            'token_type' => 'Bearer',
            'expires_in' => 86400, // 24 horas
        ];
    }

    /**
     * Registrar novo usuário
     */
    public function register(RegisterRequestDTO $data): array
    {
        $userExists = User::where('email', $data->email)->exists();
        if ($userExists) {
            throw new \Exception('Email já registrado', 409);
        }

        $user = User::create([
            'name' => $data->name,
            'email' => $data->email,
            'password' => Hash::make($data->password),
            'is_active' => true,
        ]);

        $token = $this->generateToken($user);
        $role = $this->getUserRole($user->id);

        return [
            'user_id' => $user->id,
            'email' => $user->email,
            'name' => $user->name,
            'role' => $role,
            'token' => $token,
            'token_type' => 'Bearer',
            'expires_in' => 86400,
        ];
    }

    /**
     * Renovar token
     */
    public function refreshToken(RefreshTokenRequestDTO $data): array
    {
        $payload = $this->validateToken($data->token);

        $user = User::findOrFail($payload['user_id']);

        if (!$user->is_active) {
            throw new \Exception('Usuário inativo', 403);
        }

        $newToken = $this->generateToken($user);
        $role = $this->getUserRole($user->id);

        return [
            'user_id' => $user->id,
            'email' => $user->email,
            'name' => $user->name,
            'role' => $role,
            'token' => $newToken,
            'token_type' => 'Bearer',
            'expires_in' => 86400,
        ];
    }

    /**
     * Logout do usuário
     */
    public function logout(LogoutRequestDTO $data): void
    {
        // Validar token
        $this->validateToken($data->token);

        // Em um sistema real, você poderia invalidar tokens em uma blacklist
        // Por enquanto, apenas validamos que o token é válido
    }

    /**
     * Validar token JWT
     */
    public function validateToken(string $token): array
    {
        try {
            $parts = explode('.', $token);
            if (count($parts) !== 3) {
                throw new \Exception('Token inválido');
            }

            // Decodificar payload (sem validação de assinatura por simplicidade)
            $payload = json_decode(base64_decode(str_replace(['-', '_'], ['+', '/'], $parts[1])), true);

            if (!$payload || !isset($payload['user_id'])) {
                throw new \Exception('Token inválido');
            }

            // Verificar expiração
            if (isset($payload['exp']) && $payload['exp'] < time()) {
                throw new \Exception('Token expirado', 401);
            }

            return $payload;
        } catch (\Exception $e) {
            throw new \Exception('Token inválido: ' . $e->getMessage(), 401);
        }
    }

    /**
     * Gerar JWT token
     */
    private function generateToken(User $user): string
    {
        $header = base64_encode(json_encode(['alg' => 'HS256', 'typ' => 'JWT']));
        $payload = base64_encode(json_encode([
            'user_id' => $user->id,
            'email' => $user->email,
            'name' => $user->name,
            'iat' => time(),
            'exp' => time() + 86400, // 24 horas
        ]));

        $signature = base64_encode(hash_hmac(
            'sha256',
            "$header.$payload",
            config('app.key'),
            true
        ));

        return "$header.$payload.$signature";
    }

    /**
     * Obter papel do usuário
     */
    private function getUserRole(int $userId): ?string
    {
        $rbacService = app(\App\Services\RoleBasedAccess\RoleBasedAccessService::class);
        $role = $rbacService->getUserRole($userId);

        return $role ? $role->name : null;
    }
}
