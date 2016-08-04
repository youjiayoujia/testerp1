<?php
/**
 * 数据模板控制器
 * 处理仓库相关的Request与Response
 *
 * @author: MC<178069409@qq.com>
 * Date: 15/12/18
 * Time: 15:22pm
 */

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Package\ExportModel;

class ExportPackageController extends Controller
{
    public function __construct(ExportModel $export)
    {
        $this->model = $export;
        $this->mainIndex = route('exportPackage.index');
        $this->mainTitle = '导出模板';
        $this->viewPath = 'package.export.';
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
            'fields' => config('exportPackage'),
        ];
        return view($this->viewPath . 'create', $response);
    }

    /**
     * 存储
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function store()
    {
        request()->flash();
        $model = $this->model->create(request()->all());
        $fieldNames = request('fieldNames');
        foreach($fieldNames as $fieldName) {
            $level = request($fieldName.',level') ? request($fieldName.',level') : 'Z';
            $model->items()->create(['name' => $fieldName, 'level' => $level]);
        }

        return redirect($this->mainIndex);
    }

    /**
     * 详情
     *
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function show($id)
    {
        $model = $this->model->find($id);
        if (!$model) {
            return redirect($this->mainIndex)->with('alert', $this->alert('danger', $this->mainTitle . '不存在.'));
        }
        $response = [
            'metas' => $this->metas(__FUNCTION__),
            'model' => $model,
            'exportPackageItems' => $model->items,
            'arr' => config('exportPackage')
        ];

        return view($this->viewPath . 'show', $response);
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
            'fields' => config('exportPackage'),
            'items' => $model->items,
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
        $model->update(request()->all());
        $items = $this->model->items();
        $arr_items = request('fieldNames');
        var_dump($items->toArray());
        var_dump(count($arr_items));
        exit;
        if($items->count() >= count($arr_items)) {
            var_dump('123');exit;
            foreach($items as $key => $value) {
                if(array_key_exists($key, $arr_items)) {
                    $level = request($arr_items[$key].",level");
                    $value->update(['name' => $arr_items[$key], 'level' => $level]);
                } else {
                    $value->delete();
                }
            }
        } else {
            foreach($items as $key => $value) {
                $level = request($arr_items[$key].",level");
                $value->update(['name' => $arr_items[$key], 'level' => $level]);
            }
            for($i = $items->count(); $i< count($arr_items); $i++) {
                $level = request($arr_items[$i].",level");
                $model->items()->create(['name' => $arr_items[$i], 'level' => $level]);
            }
        }
        
        return redirect($this->mainIndex);
    }
}