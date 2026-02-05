<?php

namespace App\DTOs\Requests\Posts;

use App\DTOs\Requests\DTORequest;

readonly class DeletePostRequestDTO extends DTORequest
{
    public function __construct(
        public int $id
    ) {
    }
}
