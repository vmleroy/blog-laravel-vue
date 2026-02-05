<?php

namespace App\DTOs\Requests\Auth;

use App\DTOs\Requests\DTORequest;

readonly class RegisterRequestDTO extends DTORequest
{
    public function __construct(
        public string $name,
        public string $email,
        public string $password
    ) {
    }
}
