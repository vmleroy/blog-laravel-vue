<?php

namespace App\Models\RoleBasedAccess;

use Illuminate\Database\Eloquent\Model;

class Permission extends Model
{
    protected $connection = 'rbac_db';
    protected $table = 'permissions';
    protected $fillable = ['name', 'description', 'resource'];
}
