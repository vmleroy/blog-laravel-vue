<?php

namespace App\Models\RoleBasedAccess;

use Illuminate\Database\Eloquent\Model;

class UserRole extends Model
{
    protected $connection = 'rbac_db';
    protected $table = 'user_roles';
    protected $fillable = ['user_id', 'role_id'];
    public $timestamps = false;
}
