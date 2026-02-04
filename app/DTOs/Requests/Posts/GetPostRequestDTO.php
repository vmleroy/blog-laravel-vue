<?php

namespace App\DTOs\Requests\Posts;

use App\DTOs\Requests\DTORequest;

readonly class GetPostRequestDTO extends DTORequest
{
    public function __construct(
        public int $id
    ) {
    }
}
