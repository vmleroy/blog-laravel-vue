<?php

namespace App\DTOs\Requests\Auth;

use App\DTOs\Requests\DTORequest;

readonly class LogoutRequestDTO extends DTORequest
{
    public function __construct(
        public string $token
    ) {
    }
}
