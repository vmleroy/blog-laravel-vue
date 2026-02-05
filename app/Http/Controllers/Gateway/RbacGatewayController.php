<?php

namespace App\Http\Controllers\Gateway;

use App\Http\Controllers\Controller;
use App\Http\Requests\Gateway\Rbac\GatewayCreateRoleRequest;
use App\Http\Requests\Gateway\Rbac\GatewayAssignRoleRequest;
use App\Http\Requests\Gateway\Rbac\GatewayGetUserRoleRequest;
use App\Http\Requests\Gateway\Rbac\GatewayCheckAccessRequest;
use App\Services\Proxies\RbacServiceProxy;
use Illuminate\Http\JsonResponse;

class RbacGatewayController extends Controller
{
    public function __construct(
        private RbacServiceProxy $rbacService
    ) {}

    /**
     * POST /gateway/rbac/roles
     */
    public function createRole(GatewayCreateRoleRequest $request): JsonResponse
    {
        try {
            $role = $this->rbacService->createRole(
                $request->name,
                $request->description
            );

            return response()->json($role, 201);
        } catch (\Exception $e) {
            return response()->json([
                'error' => $e->getMessage(),
            ], $e->getCode() ?: 500);
        }
    }

    /**
     * POST /gateway/rbac/roles/assign
     */
    public function assignRole(GatewayAssignRoleRequest $request): JsonResponse
    {
        try {
            $result = $this->rbacService->assignRoleToUser(
                $request->user_id,
                $request->role_id
            );

            return response()->json($result, 200);
        } catch (\Exception $e) {
            return response()->json([
                'error' => $e->getMessage(),
            ], $e->getCode() ?: 500);
        }
    }

    /**
     * GET /gateway/rbac/users/{userId}/role
     */
    public function getUserRole(GatewayGetUserRoleRequest $request, $userId): JsonResponse
    {
        try {
            $role = $this->rbacService->getUserRole((int) $userId);
            return response()->json($role, 200);
        } catch (\Exception $e) {
            return response()->json([
                'error' => $e->getMessage(),
            ], $e->getCode() ?: 404);
        }
    }

    /**
     * POST /gateway/rbac/check-access
     */
    public function checkAccess(GatewayCheckAccessRequest $request): JsonResponse
    {
        try {
            $hasAccess = $this->rbacService->checkAccess(
                $request->user_id,
                $request->resource,
                $request->action
            );

            return response()->json([
                'has_access' => $hasAccess,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'error' => $e->getMessage(),
            ], $e->getCode() ?: 500);
        }
    }
}
