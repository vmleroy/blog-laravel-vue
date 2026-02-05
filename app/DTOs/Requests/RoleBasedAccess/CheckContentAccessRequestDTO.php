<?php

namespace App\DTOs\Requests\RoleBasedAccess;

use App\DTOs\Requests\DTORequest;

readonly class CheckContentAccessRequestDTO extends DTORequest
{
    public function __construct(
        public int $user_id,
        public int $content_id,
        public string $content_type
    ) {
    }
}
