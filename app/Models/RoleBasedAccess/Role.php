<?php

namespace App\Models\RoleBasedAccess;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    protected $connection = 'rbac_db';
    protected $table = 'roles';
    protected $fillable = ['name', 'description'];
}
