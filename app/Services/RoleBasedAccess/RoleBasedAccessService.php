<?php

namespace App\Services\RoleBasedAccess;

use App\DTOs\Requests\RoleBasedAccess\CreateRoleRequestDTO;
use App\DTOs\Requests\RoleBasedAccess\AssignRoleRequestDTO;
use App\DTOs\Requests\RoleBasedAccess\CheckAccessRequestDTO;
use App\DTOs\Requests\RoleBasedAccess\SetContentAccessRequestDTO;
use App\DTOs\Requests\RoleBasedAccess\CheckContentAccessRequestDTO;
use App\Models\RoleBasedAccess\Role;
use App\Models\RoleBasedAccess\Permission;
use App\Models\RoleBasedAccess\UserRole;
use App\Models\RoleBasedAccess\RolePermission;
use App\Models\RoleBasedAccess\ContentAccess;

class RoleBasedAccessService
{
    public function createRole(CreateRoleRequestDTO $data): Role
    {
        return Role::create([
            'name' => $data->name,
            'description' => $data->description,
        ]);
    }

    public function assignRoleToUser(AssignRoleRequestDTO $data): UserRole
    {
        Role::findOrFail($data->role_id);

        UserRole::where('user_id', $data->user_id)->delete();

        return UserRole::create([
            'user_id' => $data->user_id,
            'role_id' => $data->role_id,
        ]);
    }

    public function getUserRole(int $userId): ?Role
    {
        $userRole = UserRole::where('user_id', $userId)->first();
        if (!$userRole) {
            return null;
        }

        return Role::find($userRole->role_id);
    }

    public function checkAccess(CheckAccessRequestDTO $data): bool
    {
        $userRole = UserRole::where('user_id', $data->user_id)->first();

        if (!$userRole) {
            return false;
        }

        $permission = Permission::where('resource', $data->resource)
            ->where('name', $data->action)
            ->first();

        if (!$permission) {
            return false;
        }

        $hasPermission = RolePermission::where('role_id', $userRole->role_id)
            ->where('permission_id', $permission->id)
            ->exists();

        return $hasPermission;
    }

    public function setContentAccess(SetContentAccessRequestDTO $data): ContentAccess
    {
        Role::findOrFail($data->role_id);

        ContentAccess::where('content_id', $data->content_id)
            ->where('content_type', $data->content_type)
            ->where('role_id', $data->role_id)
            ->delete();

        return ContentAccess::create([
            'content_id' => $data->content_id,
            'content_type' => $data->content_type,
            'role_id' => $data->role_id,
            'access_level' => $data->access_level,
        ]);
    }

    /**
     * access_level: 0 = sem acesso, 1 = visualizar, 2 = editar, 3 = deletar
     */
    public function checkContentAccess(CheckContentAccessRequestDTO $data, int $requiredLevel = 1): bool
    {
        $userRole = UserRole::where('user_id', $data->user_id)->first();

        if (!$userRole) {
            return false;
        }

        $contentAccess = ContentAccess::where('content_id', $data->content_id)
            ->where('content_type', $data->content_type)
            ->where('role_id', $userRole->role_id)
            ->first();

        if (!$contentAccess) {
            return false;
        }

        return $contentAccess->access_level >= $requiredLevel;
    }

    public function getRolePermissions(int $roleId): array
    {
        $rolePermissions = RolePermission::where('role_id', $roleId)
            ->with('permission')
            ->get();

        return $rolePermissions->map(fn($rp) => $rp->permission)->toArray();
    }

    public function getUsersByRole(int $roleId): array
    {
        return UserRole::where('role_id', $roleId)->pluck('user_id')->toArray();
    }
}
