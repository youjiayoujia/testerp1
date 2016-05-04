<?php

namespace App\Models;

use App\Base\BaseModel;

class RoleModel extends BaseModel
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'roles';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['id', 'role'];

    public function permission()
    {
        return $this->belongsToMany('App\Models\PermissionModel','role_permissions','role_id','permission_id');
    }
}
