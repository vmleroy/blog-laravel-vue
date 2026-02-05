<?php

namespace App\DTOs\Requests\RoleBasedAccess;

use App\DTOs\Requests\DTORequest;

readonly class SetContentAccessRequestDTO extends DTORequest
{
    public function __construct(
        public int $content_id,
        public string $content_type,
        public int $role_id,
        public int $access_level
    ) {
    }
}
