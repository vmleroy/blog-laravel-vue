<?php

namespace App\Services\Proxies;

use App\Services\RoleBasedAccess\RoleBasedAccessService;
use App\DTOs\Requests\RoleBasedAccess\CreateRoleRequestDTO;
use App\DTOs\Requests\RoleBasedAccess\AssignRoleRequestDTO;
use App\DTOs\Requests\RoleBasedAccess\CheckAccessRequestDTO;
use App\DTOs\Requests\RoleBasedAccess\CheckContentAccessRequestDTO;

class RbacServiceProxy
{
    public function __construct(
        private RoleBasedAccessService $rbacService = new RoleBasedAccessService()
    ) {}

    /**
     * Create a new role
     */
    public function createRole(string $name, ?string $description = null): array
    {
        $dto = new CreateRoleRequestDTO(name: $name, description: $description);
        return $this->rbacService->createRole($dto)->toArray();
    }

    /**
     * Assign a role to a user
     */
    public function assignRoleToUser(int $userId, int $roleId): array
    {
        $dto = new AssignRoleRequestDTO(user_id: $userId, role_id: $roleId);
        return $this->rbacService->assignRoleToUser($dto)->toArray();
    }

    /**
     * Get user's role
     */
    public function getUserRole(int $userId): ?array
    {
        $role = $this->rbacService->getUserRole($userId);
        return $role ? $role->toArray() : null;
    }

    /**
     * Check if user has access to a resource
     */
    public function checkAccess(int $userId, string $resource, string $action): bool
    {
        $dto = new CheckAccessRequestDTO(
            user_id: $userId,
            resource: $resource,
            action: $action
        );
        return $this->rbacService->checkAccess($dto);
    }

    /**
     * Check if user has access to specific content
     */
    public function checkContentAccess(int $userId, int $contentId, string $contentType, int $requiredLevel = 1): bool
    {
        $dto = new CheckContentAccessRequestDTO(
            user_id: $userId,
            content_id: $contentId,
            content_type: $contentType
        );
        return $this->rbacService->checkContentAccess($dto, $requiredLevel);
    }
}
