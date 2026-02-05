<?php

namespace App\DTOs\Requests\Auth;

use App\DTOs\Requests\DTORequest;

readonly class LoginRequestDTO extends DTORequest
{
    public function __construct(
        public string $email,
        public string $password
    ) {
    }
}
