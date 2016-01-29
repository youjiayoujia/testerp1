<?php
/**
 * 选款需求控制器
 * 处理选款需求相关的Request与Response
 *
 * @author: MC<178069409@qq.com>
 * Date: 15/12/18
 * Time: 15:21pm
 */

namespace App\Http\Controllers\Product;

use App\Http\Controllers\Controller;
use App\Models\Product\RequireModel;

class RequireController extends Controller
{
    public function __construct(RequireModel $require)
    {
        $this->model = $require;
        $this->mainIndex = route('productRequire.index');
        $this->mainTitle = '选款需求';
        $this->viewPath = 'product.require.';
    }

    /**
     * 数据保存
     *
     * @param none
     * @return view
     *
     */
    public function store()
    {
        request()->flash();
        $this->validate(request(), $this->model->rules('create'));
        $data = request()->all();
        $buf = $this->model->create($data);
        $data['id'] = $buf->id;
        for ($i = 1; $i <= 6; $i++) {
            if (request()->hasFile('img' . $i)) {
                $file = request()->file('img' . $i);
                $path = config('product.requireimage') . "/" . $data['id'];
                $dstname = $i;
                $absolute_path = $this->model->move_file($file, $dstname, $path);
                $data['img'.$i] = $absolute_path;
            }
        }
        $buf->update($data);

        return redirect(route('productRequire.index'));
    }

    /**
     * 数据更新
     *
     * @param $id integer 记录id
     * @return view
     *
     */
    public function update($id)
    {
        $model = $this->model->find($id);
        if (!$model) {
            return redirect($this->mainIndex)->with('alert', $this->alert('danger', $this->mainTitle . '不存在.'));
        }
        request()->flash();
        $this->validate(request(), $model->rules('update', $id));
        $data = request()->all();
        for ($i = 1; $i <= 6; $i++) {
            if (request()->hasFile('img' . $i)) {
                $file = request()->file('img' . $i);
                $path = config('product.requireimage') . "/" . $id;
                $dstname = $i;
                $absolute_path = $this->model->move_file($file, $dstname, $path);
                $data['img'.$i] = $absolute_path;
            }
        }
        $model->update($data);

        return redirect(route('productRequire.index'));
    }

}