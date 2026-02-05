<?php

namespace App\DTOs\Requests;

use Illuminate\Http\Request;

readonly abstract class DTORequest
{
    public static function fromRequest(Request $request): static
    {
        $validated = $request->validated();
        return new static(...$validated);
    }
}
