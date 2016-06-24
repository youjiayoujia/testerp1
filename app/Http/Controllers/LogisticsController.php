<?php
/**
 * Created by PhpStorm.
 * User: bianhaiwei
 * Date: 15/12/21
 * Time: 下午6:28
 */

namespace App\Http\Controllers;

use App\Models\Logistics\CatalogModel;
use App\Models\Logistics\CodeModel;
use App\Models\Logistics\EmailTemplateModel;
use App\Models\Logistics\LimitsModel;
use App\Models\Logistics\TemplateModel;
use App\Models\LogisticsModel;
use App\Models\WarehouseModel;
use App\Models\Logistics\SupplierModel;

class LogisticsController extends Controller
{

    public function __construct(LogisticsModel $logistics)
    {
        $this->model = $logistics;
        $this->mainIndex = route('logistics.index');
        $this->mainTitle = '物流';
        $this->viewPath = 'logistics.';
    }

    /**
     * 新建
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create()
    {
        $response = [
            'metas' => $this->metas(__FUNCTION__),
            'warehouses'=>WarehouseModel::all(),
            'suppliers'=>SupplierModel::all(),
            'limits' => LimitsModel::orderBy('id', 'asc')->get(['id', 'name']),
            'catalogs' => CatalogModel::all(),
            'emailTemplates' => EmailTemplateModel::all(),
            'templates' => TemplateModel::all(),
            'mixedSearchFields' => $this->model->mixed_search,
        ];
        return view($this->viewPath . 'create', $response);
    }

    /**
     * 编辑
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\View\View
     */
    public function edit($id)
    {
        $logistics = $this->model->find($id);
        $limits = explode(",",$logistics->limit);
        $selectedLimits = LimitsModel::whereIn('id', $limits)->get();
        if (!$logistics) {
            return redirect($this->mainIndex)->with('alert', $this->alert('danger', $this->mainTitle . '不存在.'));
        }
        $response = [
            'metas' => $this->metas(__FUNCTION__),
            'model' => $logistics,
            'warehouses'=>WarehouseModel::all(),
            'suppliers'=>SupplierModel::all(),
            'limits' => LimitsModel::orderBy('id', 'asc')->get(['id', 'name']),
            'selectedLimits' => $selectedLimits,
            'catalogs' => CatalogModel::all(),
            'emailTemplates' => EmailTemplateModel::all(),
            'templates' => TemplateModel::all(),
        ];
        return view($this->viewPath . 'edit', $response);

    }

    /**
     * 更新号码池数量
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        request()->flash();
        $array = CodeModel::distinct()->get(['logistics_id']);
        foreach($array as $key => $value)
        {
            $all = CodeModel::where(['logistics_id' => $value['logistics_id']])->count();
            $used = CodeModel::where(['logistics_id' => $value['logistics_id'], 'status' => '1'])->count();
            $unused = $all - $used;
            $pool_quantity = $unused."/".$used."/".$all;
            $arr = LogisticsModel::where(['id' => $value['logistics_id']])->get()->toArray();
            if(count($arr)) {
                foreach($arr as $k => $val)
                {
                    $model = $this->model->find($val['id']);
                    $val['pool_quantity'] = $pool_quantity;
                    $model->update(['pool_quantity' => $val['pool_quantity']]);
                }
            }
        }
        $response = [
            'metas' => $this->metas(__FUNCTION__),
            'data' => $this->autoList($this->model),
        ];
        return view($this->viewPath . 'index', $response);
    }

    /**
     *ajax获取zone
     */
    public function zone()
    {
        $id = request()->input("id");
        $buf =$this->model->find($id)->species;
        return json_encode($buf);
    }

    public function getLogistics()
    {
        $logistics_id = request('logistics');
        $logistics = $this->model->find($logistics_id);
        if(!$logistics) {
            return json_encode(false);
        }
        $str = "<option class='logis' value='".$logistics->id."'>".$logistics->logistics_type."</option>";
        return $str;
    }

}