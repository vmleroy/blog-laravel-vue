<?php

namespace App\DTOs\Requests;

use Illuminate\Http\Request;

abstract class DTORequest
{
    abstract public function __construct(mixed ...$arguments);

    public static function fromRequest(Request $request): static
    {
        $validated = $request->validated();
        return new static(...$validated);
    }
}
