<?php

namespace App\DTOs\Responses\Auth;

class AuthResponseDTO
{
    public function __construct(
        public int $user_id,
        public string $email,
        public string $name,
        public string $token,
        public string $token_type = 'Bearer',
        public ?int $expires_in = null,
    ) {
    }

    public static function fromArray(array $data): self
    {
        return new self(
            user_id: $data['user_id'],
            email: $data['email'],
            name: $data['name'],
            token: $data['token'],
            token_type: $data['token_type'] ?? 'Bearer',
            expires_in: $data['expires_in'] ?? null,
        );
    }
}
