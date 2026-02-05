<?php

namespace App\Models\RoleBasedAccess;

use Illuminate\Database\Eloquent\Model;

class RolePermission extends Model
{
    protected $connection = 'rbac_db';
    protected $table = 'role_permissions';
    protected $fillable = ['role_id', 'permission_id'];
    public $timestamps = false;
}
