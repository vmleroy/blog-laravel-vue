<?php

namespace App\Http\Requests\RoleBasedAccess;

use Illuminate\Foundation\Http\FormRequest;

class AssignRoleRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'user_id' => 'required|integer|exists:auth_db.users,id',
            'role_id' => 'required|integer|exists:rbac_db.roles,id',
        ];
    }
}
