<?php

namespace App\DTOs\Requests\Comments;

use App\DTOs\Requests\DTORequest;

readonly class UpdateCommentRequestDTO extends DTORequest
{
    public function __construct(
        public int $id,
        public ?string $body = null
    ) {
    }
}
