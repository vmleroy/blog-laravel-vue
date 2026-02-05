<?php

namespace App\Models\RoleBasedAccess;

use Illuminate\Database\Eloquent\Model;

class ContentAccess extends Model
{
    protected $connection = 'rbac_db';
    protected $table = 'content_access';
    protected $fillable = ['content_id', 'content_type', 'role_id', 'access_level'];

    protected function casts(): array
    {
        return [
            'access_level' => 'integer',
        ];
    }
}
