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

class PurchaseStockInController extends Controller
{

    public function __construct(PurchaseItemModel $purchaseStockIn)
    {
        $this->model = $purchaseStockIn;
        $this->mainIndex = route('purchaseStockIn.index');
        $this->mainTitle = '采购对单';
		$this->viewPath = 'purchase.purchaseStockIn.';
    }
    
	
	public function index()
    {
        $response = [
            'metas' => $this->metas(__FUNCTION__),
            'data' => $this->autoList($this->model->where('status',2)->orderby('storageStatus')),
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
     * 对单界面
     *
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function purchaseStockIn()
    {
        $response = [
            'metas' => $this->metas(__FUNCTION__),
        ];
        return view($this->viewPath . 'stockIn', $response);
    }
	/**
     * 对单界面
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
		foreach($purchaseItemList as $key=>$vo){
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
			}else{
				if(($data['storage_qty']+$vo->storage_qty) < $vo->purchase_num){
					$storage['storage_qty']=$data['storage_qty']+$vo->storage_qty;
					$storage['storageStatus']=1;
					$data['storage_qty']=0;					
				}elseif(($data['storage_qty']+$vo->storage_qty) == $vo->purchase_num){
					$storage['storage_qty']=$vo->purchase_num;
					$storage['storageStatus']=2;
					$data['storage_qty']=0;
				}else{
					$storage['storage_qty']=$vo->purchase_num;
					$storage['storageStatus']=2;
					$data['storage_qty']=$data['storage_qty']-$vo->purchase_num;				
				}
				}
					
				$this->model->find($vo->id)->update($storage);
				if($data['storage_qty'] == 0){
					break;
					}
				
			}
       $response = [
            'metas' => $this->metas(__FUNCTION__),
        ];
        return view($this->viewPath . 'stockIn', $response);
    }
	/**
     * 对单
     *
     * @param $id
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
	
	public function update($id)
	{
		$data=request()->all();
		$model=$this->model->find($id);
		if($data['storageInType']==1){
			if((1+$model->storage_qty) < $model->purchase_num){
				$storage['storage_qty']=$model->storage_qty+1;
				$storage['storageStatus']=1;
			}elseif((1+$model->storage_qty) == $model->purchase_num){
				$storage['storage_qty']=$model->storage_qty+1;
				$storage['storageStatus']=2;
				}else{
					return redirect($this->mainIndex)->with('alert', $this->alert('danger',  '入库数量大于了所需数量.'));	
					}
			}else{
				if(($data['storage_qty']+$model->storage_qty) < $model->purchase_num){
					$storage['storage_qty']=$data['storage_qty']+$model->storage_qty;
					$storage['storageStatus']=1;
				}elseif(($data['storage_qty']+$model->storage_qty) == $model->purchase_num){
					$storage['storage_qty']=$data['storage_qty']+$model->storage_qty;
					$storage['storageStatus']=2;
				}else{
					return redirect($this->mainIndex)->with('alert', $this->alert('danger', '入库数量大于了所需数量.'));
					}
				}		
		$model->update($storage);
        return redirect($this->mainIndex);		
	}

	/**
     * 生成条码
     *
     * @param $id
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
	public function generateDarCode($id){
		$model=$this->model->find($id);
		$res=InModel::where('relation_id',$id)->count();			
		if($res>0){
			return redirect($this->mainIndex)->with('alert', $this->alert('danger', $this->mainTitle . '已生成条码.'));		
		}else{
			$stock_num=StockModel::where('warehouse_id',$model->warehouse_id)->where('item_id',$model->item->id)->count();
			if($stock_num>0){
				$res=StockModel::where('warehouse_id',$model->warehouse_id)->where('item_id',$model->item->id)->get();
				foreach($res as $key=>$v){
					$stockInIds[$key]=$v->id;
				}
				$randKey=array_rand($stockInIds,1);
				$stock=StockModel::find($stockInIds[$randKey]);
				ItemModel::find($model->item->id)->in($stock->warehouse_position_id,$model->arrival_num, $model->purchase_cost*$model->purchase_num, 0,$model->id, $remark = '订单采购！');
				$sku=$model->sku;
				$warehouse_position_id=$stock->warehouse_position_id;
				$data['bar_code']=$sku.'#'.$warehouse_position_id;
				$model->update($data);
			}else{
				$position=PositionModel::where('warehouse_id',$model->warehouse_id)->get();
				if(count($position)==0){
					return redirect($this->mainIndex)->with('alert', $this->alert('danger', $this->mainTitle . '此仓库暂时没有库位.'));	
				}
					foreach($position as $key=>$v){
						$WarehousePositionIds[$key]=$v->id;
					}
				$randKey=array_rand($WarehousePositionIds,1);
				$stockData['warehouse_id']=$model->warehouse_id;
				$stockData['item_id']=$model->item->id;
				$stockData['warehouse_position_id']=$WarehousePositionIds[$randKey];
				$stockData['all_quantity']=$model->arrival_num;
				$stockData['hold_quantity']=$model->arrival_num;
				$stockData['amount']=$model->purchase_cost;
				$stockCreate=StockModel::create($stockData);
				$stockId=$stockCreate->id;
				ItemModel::find($model->item->id)->in($stockData['warehouse_position_id'],$model->arrival_num, $model->purchase_cost*$model->purchase_num, 0,$model->id, $remark = '订单采购！');
				$position=PositionModel::find($WarehousePositionIds[$randKey]);
				$sku=$model->sku;
				$warehouse_position_id=$stockData['warehouse_position_id'];
				$data['bar_code']=$sku.'#'.$warehouse_position_id;
				$model->update($data);
			}
		}
		return redirect($this->mainIndex);
	}
	
}









