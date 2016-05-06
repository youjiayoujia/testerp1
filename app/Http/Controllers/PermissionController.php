<?php

namespace App\Http\Controllers;

use App\Models\PermissionModel;

class PermissionController extends Controller
{
    public function __construct(PermissionModel $permission)
    {
        $this->model = $permission;
        $this->mainIndex = route('permission.index');
        $this->mainTitle = '权限';
        $this->viewPath = 'permission.';   
    }
}
