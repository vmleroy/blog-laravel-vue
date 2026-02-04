<?php

namespace App\DTOs\Requests\Comments;

use App\DTOs\Requests\DTORequest;

readonly class StoreCommentDTO extends DTORequest
{
    public function __construct(
        public int $post_id,
        public string $body
    ) {
    }
}
