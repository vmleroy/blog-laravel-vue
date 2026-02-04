<?php

namespace App\Models\Comments;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    protected $connection = 'comments_db';
    protected $fillable = ['post_id', 'body'];
}
