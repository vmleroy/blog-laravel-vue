<?php

namespace App\Http\Requests\Gateway\Auth;

use Illuminate\Foundation\Http\FormRequest;

class GatewayLogoutRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        // Logout doesn't need validation as token comes from Bearer header
        return [];
    }
}
