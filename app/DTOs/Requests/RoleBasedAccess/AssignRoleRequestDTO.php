<?php

namespace App\DTOs\Requests\RoleBasedAccess;

use App\DTOs\Requests\DTORequest;

readonly class AssignRoleRequestDTO extends DTORequest
{
    public function __construct(
        public int $user_id,
        public int $role_id
    ) {
    }
}
