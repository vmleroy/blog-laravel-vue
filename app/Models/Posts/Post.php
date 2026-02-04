<?php

namespace App\Models\Posts;

use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    protected $connection = 'posts_db';
    protected $fillable = ['title', 'content'];
}
