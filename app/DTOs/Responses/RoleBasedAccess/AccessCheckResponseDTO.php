<?php

namespace App\DTOs\Responses\RoleBasedAccess;

class AccessCheckResponseDTO
{
    public function __construct(
        public int $user_id,
        public string $resource,
        public string $action,
        public bool $allowed,
        public ?string $reason = null,
    ) {
    }
}
