<?php

namespace App\Http\Controllers;

use App\Models\RoleModel;

class RoleController extends Controller
{

    public function __construct(RoleModel $role)
    {
        $this->model = $role;
        $this->mainIndex = route('role.index');
        $this->mainTitle = '角色';
        $this->viewPath = 'role.';   
    }
}
