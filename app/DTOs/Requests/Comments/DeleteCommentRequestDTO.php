<?php

namespace App\DTOs\Requests\Comments;

use App\DTOs\Requests\DTORequest;

readonly class DeleteCommentRequestDTO extends DTORequest
{
    public function __construct(
        public int $id
    ) {
    }
}
