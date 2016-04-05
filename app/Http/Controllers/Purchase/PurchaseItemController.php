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
use App\Models\ItemModel;

class PurchaseItemController extends Controller
{

    public function __construct(PurchaseItemModel $purchaseItem )
    {
        $this->model = $purchaseItem;
        $this->mainIndex = route('purchaseItem.index');
        $this->mainTitle = '采购需求';
		$this->viewPath = 'purchase.purchaseItem.';
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
     * 创建采购条目页面
     *
     * 
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
	
	public function create()
	{
		 $response = [
			'metas' => $this->metas(__FUNCTION__),
			'warehouse' => WarehouseModel::all(),
        ];
        return view($this->viewPath . 'create', $response);		
	}
	
	/**
     * 创建采购需求
     *
     * 
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
	public function store()
	{
		$data=request()->all();
		$productItem=ItemModel::where('sku',$data['sku'])->first();
		 if (!$productItem) {
            return redirect(route('purchaseItem.create'))->with('alert', $this->alert('danger','此Item不存在!'));
        }
		$data['supplier_id']=$productItem->supplier_id;
		$data['lack_num']=$data['purchase_num'];
		$this->model->create($data);
		return redirect($this->mainIndex);
	}
	/**
     * 更新采购条目
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
        $data=request()->all();
        $this->validate(request(), $this->model->rules('update', $id));
		$data['lack_num']=$data['purchase_num']-$model->arrival_num;
        $model->update($data);
        return redirect($this->mainIndex);
    }

	/**
     * ajax批量生成采购单
     *
     * @param $id
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
	public function addPurchaseOrder()
	{
		$isadd=json_decode(request()->get('isadd'));
		if($isadd==1){ 
		$warehouse_supplier=$this->model->select('id','warehouse_id','supplier_id')->where('purchase_order_id',0)->where('active_status',0)->where('supplier_id','<>','0')->groupBy('warehouse_id')->groupBy('supplier_id')->get()->toArray();
		if(isset($warehouse_supplier)){
			foreach($warehouse_supplier as $key=>$v){
				$data['warehouse_id']=$v['warehouse_id'];		 
				$data['supplier_id']=$v['supplier_id'];
				$purchaseOrder=PurchaseOrderModel::create($data);
				$purchaseOrderId=$purchaseOrder->id; 
				if($purchaseOrderId >0){
					$this->model->where('warehouse_id',$v['warehouse_id'])->where('supplier_id',$v['supplier_id'])->where('purchase_order_id',0)->update(['purchase_order_id'=>$purchaseOrderId]); 
				}				 
			}
			
		}
		/*$warehouse_nosupplier=$this->model->select('id','warehouse_id')->where('purchase_order_id',0)->where('active_status',0)->where('supplier_id',0)->get()->toArray();
		if(isset($warehouse_supplier)){
			foreach($warehouse_supplier as $key=>$v){
				$purchaseOrderModel =new PurchaseOrderModel;
				$data['warehouse_id']=$v['warehouse_id'];	
				$purchaseOrder=$purchaseOrderModel->create($data);
				$purchaseOrderId=$purchaseOrder->id; 
				if($purchaseOrderId >0){
				$purchaseItem=$this->find($v['id']);
				$purchaseItem->purchase_order_id=$v['purchase_order_id'];
				$purchaseItem->save();
				}				 
			}
			return true;*/
		}		
	}
		
	public function costExamineStatus($id,$costExamineStatus){
		$model=$this->model->find($id);
		$data['costExamineStatus']=$costExamineStatus;
		$model->update($data);
		$num=$this->model->where('purchase_order_id',$model->purchase_order_id)->where('costExamineStatus','<>',2)->count();
		if($num==0){
			PurchaseOrderModel::where('id',$model->purchase_order_id)->update(['costExamineStatus'=>2]);
		}
		}	
		
	/**
     * 创建异常
     *
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */	
	public function activeCreate(){
		$data['id']=json_decode(request()->get('purchaseItem_id'));
		$data['active']=json_decode(request()->get('activeStatus'));
		$data['active_status']=1;
		$this->model->changActive($data);
		return 1;
	}

	/**
     * ajax编辑处理异常条目
     *
     * @param $id
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
	public function cancelThisItem($id)
	{
		$model=$this->model->find($id);
		$model->purchase_order_id=0;
		$model->save();
	}
	
	
	
	/**
     * 回传物流单号和运费
     *
     * @param $id
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
	public function form_postCoding()
	{
		$data['postCoding']=request()->get('postCoding');
		$data['purchaseItem_id']=request()->get('purchaseItem_id');
		$data['postFee']=request()->get('postFee');
		$this->model->fromPostCoding($data);
		return 1;
	}
	
	/**
     * 回传采购价格
     *
     * @param $id
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
	public function supplierCost()
	{
		$data['supplier_cost']=request()->get('supplierCost');
		$data['id']=request()->get('purchaseItem_id');
		$this->model->formSupplierCost($data);
		return 1;
	}
	
	/**
     * 更改采购条目状态
     *
     * @param $id
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
	public function changeStatus(){
		$data['itemStatus']=request()->get('itemStatus');
		$data['purchaseItem_id']=request()->get('purchaseItem_id');
		$this->model->changeItemStatus($data);
		return 1;
	}
}









