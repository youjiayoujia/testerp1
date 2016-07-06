<?php
/**
 * Created by PhpStorm.
 * User: Vincent
 * Date: 16/1/27
 * Time: 上午9:19
 */

namespace App\Http\Controllers;

use App\Models\UserModel;

class UserController extends Controller
{
    public function __construct(UserModel $user)
    {
        $this->model = $user;
        $this->mainIndex = route('user.index');
        $this->mainTitle = '用户';
        $this->viewPath = 'user.';
        
    }

    /**
     * 存储
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function store()
    {
        request()->flash();
        $this->validate(request(), $this->model->rules('create'));
        $data = request()->all();
        $data['password'] = bcrypt($data['password']);
        $this->model->create($data);
        return redirect($this->mainIndex)->with('alert', $this->alert('success', '添加成功.'));
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
        $response = [
            'metas' => $this->metas(__FUNCTION__),
            'model' => $model,
        ];
        return view($this->viewPath . 'edit', $response);
    }

    /**
     * 更新
     *
     * @param $id
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function update($id)
    {
        $model = $this->model->find($id);
        if (!$model) {
            return redirect($this->mainIndex)->with('alert', $this->alert('danger', $this->mainTitle . '不存在.'));
        }
        request()->flash();
        $this->validate(request(), $this->model->rules('update', $id));
        $data = request()->all();
        if(strlen($data['password'])>=30){
            $data['password'] = $data['password'];
        }else{
            $data['password'] = bcrypt($data['password']);
        }
        $model->update($data);
        return redirect($this->mainIndex)->with('alert', $this->alert('success', '更新成功.'));
    }

    /**
     * 获取供应商信息
     */
    public function ajaxUser()
    {
        if(request()->ajax()) {
            $user = trim(request()->input('user'));
            $buf = UserModel::where('name', 'like', '%'.$user.'%')->get();
            $total = $buf->count();
            $arr = [];
            foreach($buf as $key => $value) {
                $arr[$key]['id'] = $value->name;
                $arr[$key]['text'] = $value->name;
            }
            if($total)
                return json_encode(['results' => $arr, 'total' => $total]);
            else
                return json_encode(false);
        }

        return json_encode(false);
    }

}