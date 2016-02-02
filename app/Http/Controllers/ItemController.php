<?php
/**
 * item控制器
 *
 * User: youjia
 * Date: 16/1/18
 * Time: 09:32:00
 */

namespace App\Http\Controllers;

use App\Models\ItemModel;
use App\Models\Product\SupplierModel;

class ItemController extends Controller
{
    public function __construct(ItemModel $item,SupplierModel $supplier)
    {
        $this->model = $item;
        $this->supplier = $supplier;
        $this->mainIndex = route('item.index');
        $this->mainTitle = 'item';
        $this->viewPath = 'item.';
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
            'suppliers' => $this->supplier->all(),
        ];
        return view($this->viewPath . 'edit', $response);
    }

}