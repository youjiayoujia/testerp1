<?php
/**
 * 仓库控制器
 * 处理仓库相关的Request与Response
 *
 * @author: MC<178069409@qq.com>
 * Date: 15/12/18
 * Time: 15:22pm
 */

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\WarehouseModel;

class WarehouseController extends Controller
{
    public function __construct(WarehouseModel $warehouse)
    {
        $this->model = $warehouse;
        $this->mainIndex = route('warehouse.index');
        $this->mainTitle = '仓库';
        $this->viewPath = 'warehouse.';
    }

    /**
     * 新建
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create()
    {
        $response = [
            'metas' => $this->metas(__FUNCTION__),
            'count' => $this->model->where(['is_available'=>'1', 'is_default'=>'1'])->count(),
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
        $response = [
            'metas' => $this->metas(__FUNCTION__),
            'model' => $model,
            'count' => $this->model->where(['is_available'=>'1', 'is_default'=>'1'])->count(),
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
        $model->update(request()->all());
        if(request()->input('is_available') == '0')
        {
            if($model->position) {
                $positions = $model->position;
                foreach($positions as $position)
                {
                    if($position->stock) {
                        $stocks = $position->stock;
                        foreach($stocks as $stock) {
                            $stock->delete();
                        }
                    }
                    $position->update(['is_available' => '0']);
                }
            }
        }

        return redirect($this->mainIndex);
    }

    /**
     * 删除
     *
     * @param $id
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function destroy($id)
    {
        $model = $this->model->find($id);
        if (!$model) {
            return redirect($this->mainIndex)->with('alert', $this->alert('danger', $this->mainTitle . '不存在.'));
        }
        if($model->position) {
            $positions = $model->position;
            foreach($positions as $position)
            {
                if($position->stock) {
                    $stocks = $position->stock;
                    foreach($stocks as $stock) {
                        $stock->delete();
                    }
                }
                $position->update(['is_available' => '0']);
            }
        }
        $model->destroy($id);
        
        return redirect($this->mainIndex);
    }
}