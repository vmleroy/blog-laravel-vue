<?php

namespace App\Http\Requests\RoleBasedAccess;

use Illuminate\Foundation\Http\FormRequest;

class SetContentAccessRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'content_id' => 'required|integer',
            'content_type' => 'required|string|max:255',
            'role_id' => 'required|integer|exists:rbac_db.roles,id',
            'access_level' => 'required|integer|min:0|max:3',
        ];
    }
}
