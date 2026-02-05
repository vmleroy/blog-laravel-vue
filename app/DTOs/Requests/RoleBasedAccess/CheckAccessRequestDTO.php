<?php

namespace App\DTOs\Requests\RoleBasedAccess;

use App\DTOs\Requests\DTORequest;

readonly class CheckAccessRequestDTO extends DTORequest
{
    public function __construct(
        public int $user_id,
        public string $resource,
        public string $action = 'view'
    ) {
    }
}
