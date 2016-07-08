<?php

namespace App\Http\Controllers;

use App\Models\RoleModel;
use App\Models\PermissionModel;

class RoleController extends Controller
{

    public function __construct(RoleModel $role)
    {
        $this->model = $role;
        $this->mainIndex = route('role.index');
        $this->mainTitle = '角色';
        $this->viewPath = 'role.';   
    }

    public function create()
    {
        $response = [
            'metas' => $this->metas(__FUNCTION__),
            'permissions' => PermissionModel::all(),
        ];

        return view($this->viewPath . 'create', $response);
    }

    /**
     * 编辑
     *
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit($id)
    {
        $model = $this->model->find($id);
        if (!$model) {
            return redirect($this->mainIndex)->with('alert', $this->alert('danger', $this->mainTitle . '不存在.'));
        }
        //echo '<pre>';
        //print_r($model->role->toArray());exit;
        $select_permission = [];
        foreach($model->permission as $permission){
            $select_permission[] = $permission->pivot->permission_id;
        }
        $response = [
            'metas' => $this->metas(__FUNCTION__),
            'model' => $model,
            'permissions' => PermissionModel::all(),
            'select_permission' => $select_permission,
        ];
        return view($this->viewPath . 'edit', $response);
    }

    public function store() 
    {
        request()->flash();
        $this->validate(request(), $this->model->rules('create'));
        $data = request()->all();
        $roleModel = $this->model->create($data);
        //多对多插入role_permissons表
        $roleModel->permission()->attach($data['role_permission']);

        return redirect($this->mainIndex);
    }
}
