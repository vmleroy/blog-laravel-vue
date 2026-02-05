<?php

namespace App\Http\Requests\RoleBasedAccess;

use Illuminate\Foundation\Http\FormRequest;

class CheckAccessRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'user_id' => 'required|integer|exists:auth_db.users,id',
            'resource' => 'required|string|max:255',
            'action' => 'required|string|max:255',
        ];
    }
}
