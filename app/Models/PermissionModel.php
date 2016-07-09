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

    public $rules = [
        'create' => [
            'action' => 'required|unique:permissions,action',
            'action_name' => 'required|unique:permissions,action_name',
        ],
        'update' => [
            'action' => 'required|unique:permissions,action,{id}',
            'action_name' => 'required|unique:permissions,action_name,{id}',
        ]
    ];
    public $searchFields = ['name', 'id'];
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['id', 'action_name','action'];

    public function role()
    {
        return $this->belongsToMany('App\Models\RoleModel','role_permissions','permission_id','role_id');
    }
}
