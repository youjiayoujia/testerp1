<?php
/**
 * 海外仓头程物流控制器
 *
 * 2016-12.05
 * @author: MC<178069409>
 */

namespace App\Http\Controllers\Oversea;

use App\Http\Controllers\Controller;
use App\Models\Oversea\FirstLeg\FirstLegModel;
use App\Models\WarehouseModel;

class FirstLegController extends Controller
{
    public function __construct(FirstLegModel $firstLeg)
    {
        $this->model = $firstLeg;
        $this->mainIndex = route('firstLeg.index');
        $this->mainTitle = '海外仓头程物流';
        $this->viewPath = 'oversea.firstLeg.';
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
            'warehouses' => WarehouseModel::where(['type' => 'fbaLocal', 'is_available' => '1'])->get(),
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
            'warehouses' => WarehouseModel::where(['type' => 'fbaLocal', 'is_available' => '1'])->get(),
        ];
        return view($this->viewPath . 'edit', $response);
    }
}