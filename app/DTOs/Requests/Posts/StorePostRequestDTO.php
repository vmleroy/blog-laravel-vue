<?php

namespace App\DTOs\Requests\Posts;

use App\DTOs\Requests\DTORequest;

readonly class StorePostRequestDTO extends DTORequest
{
    public function __construct(
        public string $title,
        public string $content
    ) {
    }
}
