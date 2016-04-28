<?php
/**
 * 采购条目控制器
 * 处理图片相关的Request与Response
 *
 * User: tup
 * Date: 16/1/4
 * Time: 下午5:02
 */

namespace App\Http\Controllers\Purchase;

use App\Http\Controllers\Controller;
use App\Models\Purchase\PurchaseItemModel;
use App\Models\Purchase\PurchaseOrderModel;
use App\Models\WarehouseModel;
use App\Models\Product\SupplierModel;
use App\Models\ItemModel;
use App\Models\StockModel;
use App\Models\Stock\InModel;
use App\Models\Warehouse\PositionModel;
use App\Models\Purchase\StorageLogModel;

class PurchaseStockInController extends Controller
{

    public function __construct(PurchaseItemModel $purchaseStockIn)
    {
        $this->model = $purchaseStockIn;
        $this->mainIndex = route('purchaseStockIn.index');
        $this->mainTitle = '采购入库';
		$this->viewPath = 'purchase.purchaseStockIn.';
    }
    
	
	public function index()
    {
        $response = [
            'metas' => $this->metas(__FUNCTION__),
            'data' => $this->autoList($this->model->where('status',2)->orderBy('storageStatus', 'desc')),
        ];
        return view($this->viewPath . 'index', $response);
    }
	
	/**
     * 对单界面
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
     * 批量入库
     *
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function updateStorage()
    {
        $data=request()->all();
		if($data['storageInType']==1){
			$data['storage_qty']=1;
		}
		$purchaseItemList=$this->model->where('sku',$data['sku'])->where('status','2')->orderby('storageStatus')->get();
		$storage_num=$this->model->where('sku',$data['sku'])->where('status','2')->sum('lack_num');
		if($storage_num == 0){
			return redirect(route('purchaseStockIn.create'))->with('alert', $this->alert('danger', $this->mainTitle . '没有可入库条目.'));
		}
		foreach($purchaseItemList as $key=>$vo){
			if($vo->bar_code){
			if($data['storageInType']==1){
				if(($data['storage_qty']+$vo->storage_qty) < $vo->purchase_num){
					$storage['storage_qty']=$vo->storage_qty+$data['storage_qty'];
					$storage['storageStatus']=1;
					$data['storage_qty']=0;
				}elseif(($data['storage_qty']+$vo->storage_qty) == $vo->purchase_num){
					$storage['storage_qty']=$vo->storage_qty+$data['storage_qty'];
					$storage['storageStatus']=2;
					$data['storage_qty']=0;
				}
				$stoeagelog['storage_quantity']=1;
			}else{
				if(($data['storage_qty']+$vo->storage_qty) < $vo->purchase_num){
					$storage['storage_qty']=$data['storage_qty']+$vo->storage_qty;
					$storage['storageStatus']=1;
					$stoeagelog['storage_quantity']=$data['storage_qty'];
					$data['storage_qty']=0;					
				}elseif(($data['storage_qty']+$vo->storage_qty) == $vo->purchase_num){
					$storage['storage_qty']=$vo->purchase_num;
					$storage['storageStatus']=2;
					$stoeagelog['storage_quantity']=$data['storage_qty'];
					$data['storage_qty']=0;
				}else{
					$storage['storage_qty']=$vo->purchase_num;
					$storage['storageStatus']=2;
					$stoeagelog['storage_quantity']=$vo->purchase_num - $vo->storage_qty;
					$data['storage_qty']=$data['storage_qty']-$vo->purchase_num;				
				}
			}
		}
				if($data['storage_qty'] == 0){
					return redirect(route('purchaseStockIn.create'))->with('alert', $this->alert('danger', $this->mainTitle . '仓库没有库位.'));
					}	
				$this->model->find($vo->id)->update($storage);
				$stoeagelog['user_id']=1;
				$stoeagelog['purchaseItemId']=$vo->id;
				if($stoeagelog['storage_quantity']>0){
				StorageLogModel::create($stoeagelog);
				$stock=StockModel::find($vo->stock_id);
				ItemModel::find($vo->item->id)->in($stock->warehouse_position_id,$stoeagelog['storage_quantity'], $vo->purchase_cost*$stoeagelog['storage_quantity'], "PURCHASE",$vo->id, $remark = '订单采购入库！');
				}			
			}
	
       $response = [
            'metas' => $this->metas(__FUNCTION__),
        ];
        return view($this->viewPath . 'stockIn', $response);
    }
 
}









