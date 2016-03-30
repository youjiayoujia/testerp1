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
use App\Models\WarehouseModel;

class PurchaseItemController extends Controller
{

    public function __construct(PurchaseItemModel $purchaseItem,WarehouseModel $warehouse )
    {
        $this->model = $purchaseItem;
		$this->warehouse = $warehouse;
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
     * 创建采购条目
     *
     * @param $id
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
	
	public function store()
	{
		$data=request()->all();
		$this->model->purchasestore($data);
        return redirect($this->mainIndex);		
	}

	/**
     * 创建采购条目
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

	
	public function addPurchaseOrder()
	{
		$isadd=json_decode(request()->get('isadd'));
		if($isadd==1){ 
			$res=$this->model->purchaseOrderCreate();
			if($res  == true){
			return 1;
			}else{
			return 0;	
				}
		}
	}
	
	public function cancelThisItem($id)
	{
		$this->model->cancelOrderItem($id);
	}
	
	public function activeCreate(){
		$data['id']=json_decode(request()->get('purchaseItem_id'));
		$data['active']=json_decode(request()->get('activeStatus'));
		$data['active_status']=1;
		$this->model->changActive($data);
		return 1;
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









