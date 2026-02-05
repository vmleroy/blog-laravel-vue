<?php

namespace App\DTOs\Requests\RoleBasedAccess;

use App\DTOs\Requests\DTORequest;

readonly class CreateRoleRequestDTO extends DTORequest
{
    public function __construct(
        public string $name,
        public ?string $description = null
    ) {
    }
}
