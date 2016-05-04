<?php

namespace App\Models;

use App\Base\BaseModel;

class PermissionModel extends BaseModel
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'permissions';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['id', 'permission'];

    public function role()
    {
        return $this->belongsToMany('App\Models\RoleModel','role_permissions','permission_id','role_id');
    }
}
