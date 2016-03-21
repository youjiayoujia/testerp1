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
use App\Models\Purchase\PurchaseOrderModel;
use App\Models\Purchase\PurchaseItemModel;
use App\Models\WarehouseModel;
use App\Models\Product\SupplierModel;

class PurchaseOrderController extends Controller
{

    public function __construct(PurchaseOrderModel $purchaseOrder,WarehouseModel $warehouse,PurchaseItemModel $purchaseItem,SupplierModel $supplier )
    {
        $this->model = $purchaseOrder;
		$this->warehouse = $warehouse;
		$this->purchaseItem=$purchaseItem;
		$this->supplier=$supplier;
        $this->mainIndex = route('purchaseOrder.index');
        $this->mainTitle = '采购单';
		$this->viewPath = 'purchase.purchaseOrder.';
    }
    
	
	public function index()
    {
        $response = [
            'metas' => $this->metas(__FUNCTION__),
            'data' => $this->autoList($this->model),
        ];
        return view($this->viewPath . 'index', $response);
    }
	
	/**
     * 创建采购单页面
     *
     * @param $id
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
	
	public function create()
	{	
		$response = [
			'metas' => $this->metas(__FUNCTION__),
			'warehouse' => $this->warehouse->all(),
        ];
        return view($this->viewPath . 'create', $response);		
	}
	
 	/**
     * 创建采购条目页面
     *
     * @param $id
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
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
			'purchaseItems'=>$this->purchaseItem->where('purchase_order_id',$id)->get(),
        ];
        return view($this->viewPath . 'edit', $response);	
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
			'purchaseItems'=>$this->purchaseItem->where('purchase_order_id',$id)->get(),
        ];
        return view($this->viewPath . 'show', $response);
    }
	
	/**
     * 创建采购条目
     *
     * @param $id
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
	
	public function store()
	{	
		$data=request()->all();
		$this->model->addPurchaseOrder($data);
        return redirect($this->mainIndex);	
	}
	
	/**
     * ajax获得仓库对应采购需求的供应商
     *
     * @param $id
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
	
	public function purchaseOrderSupplier()
	{
		$warehouse_id=json_decode(request()->get('warehouse_id'));
		if($warehouse_id==''){
            return 0;
      	}else{
		$data = $this->purchaseItem->all()->where('warehouse_id',$warehouse_id)->toArray();
			foreach($data as $key=>$value){
				$supplier_ids[$key]=$value['supplier_id'];
			}
		if(!isset($supplier_ids)){
			return 0;
		}else{
			$warehouseSupplier_ids=array_unique($supplier_ids);
			$i=0;
			foreach($warehouseSupplier_ids as $key=>$v){
				$res[$i]=$this->supplier->find($v)->toArray();
				$i++;
			}
			$result['num']=count($res);
			$result['res']=array_values($res);
        return $result;	
		}
		}
	}
	
	/**
     * ajax获得仓库对应采购商的所有采购需求
     *
     * @param $id
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
	
	public function checkProductItems()
	{
		$warehouse_id=json_decode(request()->get('warehouse_id'));
		$supplier_id=json_decode(request()->get('supplier_id'));
		if($warehouse_id=='' || $supplier_id==''){
            return 0;
      	}else{
		$data = $this->purchaseItem->all()->where('warehouse_id',$warehouse_id)->where('supplier_id',$supplier_id)->where('purchase_order_id','!>',0);
        return view($this->viewPath . 'ajaxPurchaseItems',['data' => $data]);	
		}
	}

	/**
     * ajax获得仓库对应采购商的已选取的采购条目
     *
     * @param $id
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */	
	public function checkedPurchaseItem()
	{
		$purchaseItemIds=explode(',',request()->get('purchaseItemIds'));
		foreach($purchaseItemIds as $key=>$val){
		$res[$key] = $this->purchaseItem->find($val);
		}
		$data=array_values($res);
		return view($this->viewPath . 'ajaxCheckedPurchaseItems',['data' => $data]);
	}
	
	
	/**
     * 更新采购单
     *
     * @param $id
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
	
	public function update($id)
	{
		$data=request()->all();
		$this->model->purchaseItemUpdate($id,$data);
        return redirect($this->mainIndex);		
	}
	
	/**
     * 审核采购单
     *
     * @param $id
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
	
	public function examinePurchaseOrder()
	{
		$purchaseOrderIds=explode(',',request()->get('purchase_ids'));
		$this->model->updatePurchaseOrderExamine($purchaseOrderIds);
		return 1;
	}
	
	public function changeStatus(){
		$data['itemStatus']=request()->get('itemStatus');
		$data['purchaseItem_id']=request()->get('purchaseItem_id');
		$this->model->changeItemStatus($data);
		return 1;
	}
	//回传物流单号
	public function form_postCoding()
	{
		$data['postCoding']=request()->get('postCoding');
		$data['purchaseItem_id']=request()->get('purchaseItem_id');
		$data['postFee']=request()->get('postFee');
		$this->model->fromPostCoding($data);
		return 1;
		}
	public function supplierCost()
	{
		$data['supplier_cost']=request()->get('supplierCost');
		$data['id']=request()->get('purchaseItem_id');
		$this->model->formSupplierCost($data);
		return 1;
		}
		
	public function printOrder($id)
	{		
		echo 111;exit;
	}
	
	public function excelOut($id)
	{
		$this->model->purchaseOrderExcelOut($id);	
	}
	public function cancelOrder($id){
		$this->model->cancelOrderItems($id);
		 return redirect($this->mainIndex);	
		}
}









