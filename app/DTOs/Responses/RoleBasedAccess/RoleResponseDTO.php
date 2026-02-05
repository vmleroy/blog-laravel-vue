<?php

namespace App\DTOs\Responses\RoleBasedAccess;

class RoleResponseDTO
{
    public function __construct(
        public int $id,
        public string $name,
        public ?string $description = null,
    ) {
    }

    public static function fromModel($role): self
    {
        return new self(
            id: $role->id,
            name: $role->name,
            description: $role->description,
        );
    }
}
