<?php

namespace App\Http\Requests\RoleBasedAccess;

use Illuminate\Foundation\Http\FormRequest;

class CreateRoleRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255|unique:rbac_db.roles,name',
            'description' => 'nullable|string|max:500',
        ];
    }
}
