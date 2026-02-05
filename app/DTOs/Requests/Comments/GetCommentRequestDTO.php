<?php

namespace App\DTOs\Requests\Comments;

use App\DTOs\Requests\DTORequest;

readonly class GetCommentRequestDTO extends DTORequest
{
    public function __construct(
        public int $id
    ) {
    }
}
