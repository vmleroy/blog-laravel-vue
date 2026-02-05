<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\RoleBasedAccess\CreateRoleRequest;
use App\Http\Requests\RoleBasedAccess\AssignRoleRequest;
use App\Http\Requests\RoleBasedAccess\CheckAccessRequest;
use App\Http\Requests\RoleBasedAccess\SetContentAccessRequest;
use App\Http\Requests\RoleBasedAccess\CheckContentAccessRequest;
use App\DTOs\Requests\RoleBasedAccess\CreateRoleRequestDTO;
use App\DTOs\Requests\RoleBasedAccess\AssignRoleRequestDTO;
use App\DTOs\Requests\RoleBasedAccess\CheckAccessRequestDTO;
use App\DTOs\Requests\RoleBasedAccess\SetContentAccessRequestDTO;
use App\DTOs\Requests\RoleBasedAccess\CheckContentAccessRequestDTO;
use App\DTOs\Responses\RoleBasedAccess\RoleResponseDTO;
use App\DTOs\Responses\RoleBasedAccess\AccessCheckResponseDTO;
use App\Services\RoleBasedAccess\RoleBasedAccessService;
use Illuminate\Http\JsonResponse;

class RoleBasedAccessController extends Controller
{
    public function __construct(protected RoleBasedAccessService $rbacService) {}

    /**
     * POST /api/v1/rbac/roles
     */
    public function createRole(CreateRoleRequest $request): JsonResponse
    {
        try {
            $dto = CreateRoleRequestDTO::fromRequest($request);
            $role = $this->rbacService->createRole($dto);
            return response()->json(RoleResponseDTO::fromModel($role), 201);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }

    /**
     * POST /api/v1/rbac/users/{userId}/role
     */
    public function assignRole(AssignRoleRequest $request): JsonResponse
    {
        try {
            $dto = AssignRoleRequestDTO::fromRequest($request);
            $this->rbacService->assignRoleToUser($dto);
            return response()->json(['message' => 'Role atribuído com sucesso'], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }

    /**
     * GET /api/v1/rbac/users/{userId}/role
     */
    public function getUserRole($userId): JsonResponse
    {
        try {
            $role = $this->rbacService->getUserRole($userId);
            if (!$role) {
                return response()->json(['message' => 'Usuário sem role atribuído'], 404);
            }
            return response()->json(RoleResponseDTO::fromModel($role), 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }

    /**
     * POST /api/v1/rbac/check-access
     */
    public function checkAccess(CheckAccessRequest $request): JsonResponse
    {
        try {
            $dto = CheckAccessRequestDTO::fromRequest($request);
            $allowed = $this->rbacService->checkAccess($dto);

            return response()->json(new AccessCheckResponseDTO(
                user_id: $dto->user_id,
                resource: $dto->resource,
                action: $dto->action,
                allowed: $allowed,
                reason: $allowed ? null : 'Acesso negado',
            ), 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }

    /**
     * POST /api/v1/rbac/content-access
     */
    public function setContentAccess(SetContentAccessRequest $request): JsonResponse
    {
        try {
            $dto = SetContentAccessRequestDTO::fromRequest($request);
            $this->rbacService->setContentAccess($dto);
            return response()->json(['message' => 'Acesso a conteúdo definido com sucesso'], 201);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }

    /**
     * POST /api/v1/rbac/check-content-access
     */
    public function checkContentAccess(CheckContentAccessRequest $request): JsonResponse
    {
        try {
            $dto = CheckContentAccessRequestDTO::fromRequest($request);
            $allowed = $this->rbacService->checkContentAccess($dto);

            return response()->json([
                'user_id' => $dto->user_id,
                'content_id' => $dto->content_id,
                'content_type' => $dto->content_type,
                'allowed' => $allowed,
            ], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }
}
