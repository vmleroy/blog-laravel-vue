<?php

namespace App\Models\Comments;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    protected $connection = 'comments_db';
    protected $fillable = ['user_id', 'post_id', 'body'];
}
