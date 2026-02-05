<?php

namespace App\Http\Requests\RoleBasedAccess;

use Illuminate\Foundation\Http\FormRequest;

class CheckContentAccessRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'user_id' => 'required|integer|exists:auth_db.users,id',
            'content_id' => 'required|integer',
            'content_type' => 'required|string|max:255',
        ];
    }
}
