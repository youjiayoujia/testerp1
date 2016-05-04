<?php
/**
 * 库存控制器
 * 处理库存相关的Request与Response
 *
 * @author: MC<178069409@qq.com>
 * Date: 15/12/30
 * Time: 14:19pm
 */

namespace App\Http\Controllers;

use Cache;
use Maatwebsite\Excel\Facades\Excel; 
use App\Models\StockModel;
use App\Models\WarehouseModel;
use App\Models\ItemModel;
use App\Models\Warehouse\PositionModel;
use App\Models\Stock\TakingModel;
use App\Models\Stock\TakingAdjustmentModel;
use App\Models\Stock\TakingFormModel;

class StockController extends Controller
{
    public function __construct(StockModel $stock)
    {
        $this->model = $stock;
        $this->mainIndex = route('stock.index');
        $this->mainTitle = '库存开帐';
        $this->viewPath = 'stock.';
        $this->middleware('StockIOStatus');
    }

    /**
     * 跳转创建页
     *
     * @param none
     * @return view
     *
     */
    public function create()
    {
        $response = [
            'metas' => $this->metas(__FUNCTION__),
            'warehouses' => WarehouseModel::where('is_available','1')->get(),
        ];

        return view($this->viewPath.'create', $response);
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
        $item_id = ItemModel::where('sku', trim(request()->input('sku')))->first()->id;
        $warehouse_position_id = PositionModel::where(['name' => trim(request()->input('warehouse_position_id')), 'is_available' => '1'])->first()->id;
        ItemModel::find($item_id)->in($warehouse_position_id, request()->input('all_quantity'), request()->input('all_quantity') * request()->input('unit_cost'), 'MAKE_ACCOUNT');
        return redirect($this->mainIndex);
    }

    /**
     * 盘点更新
     *
     * @return
     *
     */
    public function createTaking()
    {
        Cache::store('file')->forever('stockIOStatus', '0');
        $taking = TakingModel::create(['taking_id'=>'PD'.time()]);
        $stocks = $this->model->all();
        foreach($stocks as $stock) 
        {
            $stock->stockTakingForm()->create(['stock_taking_id'=>$taking->id]);
        }

        return redirect(route('stockTaking.index'));
    }
    /**
     * 获取库存对象，通过库位
     * 某仓库某库位的对象里面的所有sku
     *
     * @return obj
     * @var array
     *
     */
    public function ajaxGetByPosition()
    {
        $warehouse_id = trim(request()->input('warehouse_id'));
        $position = PositionModel::where(['name' => trim(request()->input('position')), 'warehouse_id'=>$warehouse_id, 'is_available' => '1'])->first();
        if(!$position) {
            return json_encode(false);
        }
        $type = request()->input('type');
        $warehouse_position_id = $position->id;
        $sku = trim(request()->input('sku'));
        $item_id = ItemModel::where('sku', $sku)->first()->id;
        $obj = StockModel::where(['warehouse_position_id'=>$warehouse_position_id, 'item_id'=>$item_id])->first();
        if($obj) {
            return json_encode($obj->available_quantity);
        }
        if(StockModel::where(['warehouse_id'=>$warehouse_id, 'item_id'=>$item_id])->count() < 2) {
            return json_encode(true);
        } else {
            return json_encode(false);
        }
    }

    /**
     * 获取信息 
     * 传参：sku，仓库号
     * array[0] => item号的相应对象
     * array[1] => 通过仓库和items_id 来获取对应的库存对象
     * array[2] => 对应于array[1]的position对象
     * array[3] => 获取商品单价
     *
     * @return array
     */
    public function ajaxGetMessage()
    {
            $sku = request()->input('sku');
            $warehouse_id = request()->input('warehouse_id');
            $obj = ItemModel::where(['sku'=>$sku])->first();
            if(!$obj) {
                return json_encode('sku_none');
            }
            $obj1 = StockModel::where(['warehouse_id'=>$warehouse_id, 'item_id'=>$obj->id])->with('position')->get();
            if(!count($obj1)) {
                return json_encode('stock_none');
            }
            $arr[] = $obj1->toArray();
            $arr[] = $obj1->first()->unit_cost;
            if($arr) {
                return json_encode($arr);
            } else {
                return json_encode('false');
            }
    }

    /**
     * 调拨调出仓库对应的ajax调用
     *
     * @param none
     * @return json
     *
     */
    public function ajaxAllotOutWarehouse()
    {
        if(request()->ajax()) {
            $warehouse = request()->input('warehouse');
            $buf = $this->model->where('warehouse_id', $warehouse)->distinct()->with('items')->get(['item_id'])->toArray();
            if(!count($buf)) {
                return json_encode('none');
            }
            $arr[] = $buf;
            $arr[] = $this->model->where('warehouse_id', $warehouse)->first()->toArray();
            $obj = $this->model->where(['warehouse_id'=>$warehouse, 'item_id'=>$arr[0][0]['items']['id']])->get();
            foreach($obj as $val)
            {
                $tmp = $val->position ? $val->position->toArray() : '';
                $arr[2][] = $tmp;
            }
            return json_encode($arr);
        }

        return json_encode('false');
    }

    /**
     * 调拨库位对应的ajax调用
     *
     * @param none
     * @return json
     *
     */
    public function ajaxAllotPosition()
    {
        if(request()->ajax()) {
            $position = PositionModel::where('name', trim(request()->input('position')))->first();
            if(!$position) {
                return json_encode(false);
            }
            $position = $position->id;
            $item_id = ItemModel::where('sku', trim(request()->input('sku')))->first()->id;
            $obj = StockModel::where(['warehouse_position_id'=>$position, 'item_id'=>$item_id])->first();
            $arr[] = $obj->toArray();
            $arr[] = $obj->unit_cost;
            if($arr) {
                return json_encode($arr);
            } else {
                return json_encode('none');
            }
        }

        return json_encode(false);
    }

    /**
     * 调拨sku对应的ajax调用
     *
     * @param none
     * @return json
     *
     */
    public function ajaxAllotSku()
    {
        if(request()->ajax()) {
            $warehouse = trim(request()->input('warehouse'));
            $sku = trim(request()->input('sku'));
            if(!ItemModel::where('sku', $sku)->count()) {
                return json_encode('none');
            }
            $item_id = ItemModel::where('sku', $sku)->first()->id;
            $obj = StockModel::where(['warehouse_id'=>$warehouse, 'item_id'=>$item_id])->with('position')->get();
            if(!count($obj)) {
                return json_encode('none');
            }
            $arr[] = $obj;
            $arr[] = $obj->first()->available_quantity;
            $arr[] = $obj->first()->unit_cost;
            
            return json_encode($arr);
        }

        return json_encode('false');
    }

    /**
     * ajax请求  sku
     *
     * @param none
     * @return obj
     * 
     */
    public function ajaxSku()
    {
        if(request()->ajax()) {
            $sku = trim(request()->input('sku'));
            $count = ItemModel::where('sku', $sku)->count();
            if($count)
                return json_encode('true');
            else 
                return json_encode('false');
        }

        return json_encode('false');
    }

    /**
     * ajax请求   position
     *
     * @param none
     * @return boolean
     *
     */
    public function ajaxPosition()
    {
        $sku = trim(request()->input('sku'));
        $obj = ItemModel::where('sku', $sku)->first();
        if(!$obj) {
            return json_encode(false);
        }
        $position = PositionModel::where(['name' => trim(request()->input('position')), 'is_available'=>'1'])->first();
        if(!$position) {
            return json_encode(false);
        }
        $stock = StockModel::where(['item_id'=>$obj->id, 'warehouse_position_id'=>$position->id])->first();
        if($stock)
            return json_encode($stock->available_quantity);
        else
            return json_encode(false);
    }

    /**
     * 获取excel表格 
     *
     * @param none
     *
     */
    public function getExcel()
    {
        $rows = [
                    [ 
                     'sku'=>'',
                     'position'=>'',
                     'all_quantity'=>'',
                     'available_quantity'=>'',
                     'hold_quantity'=>'',
                     'unit_cost'=>'',
                    ]
            ];
        $name = 'stock';
        Excel::create($name, function($excel) use ($rows){
            $nameSheet='库存';
            $excel->sheet($nameSheet, function($sheet) use ($rows){
                $sheet->with($rows);
            });
        })->download('csv');
    }

    /**
     * excel 导入数据
     *
     * @param
     *
     */
    public function importByExcel()
    {
        $response = [
            'metas' => $this->metas(__FUNCTION__),
        ];

        return view($this->viewPath.'excel', $response);
    }

    /**
     * excel 处理
     *
     * @param none
     *
     */
    public function excelProcess()
    {
        if(request()->hasFile('excel'))
        {
            $file = request()->file('excel');
            $errors = $this->model->excelProcess($file);
            $response = [
                'metas' => $this->metas(__FUNCTION__),
                'errors' => $errors,
            ];
            return view($this->viewPath.'excelResult', $response);
        }
    }
}