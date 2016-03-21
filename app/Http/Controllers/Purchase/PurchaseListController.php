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
use App\Models\Purchase\PurchaseListModel;
use App\Models\WarehouseModel;
use App\Models\Product\SupplierModel;

class PurchaseListController extends Controller
{

    public function __construct(PurchaseListModel $purchaseList,WarehouseModel $warehouse,SupplierModel $supplier)
    {
        $this->model = $purchaseList;
		$this->warehouse = $warehouse;
		$this->supplier=$supplier;
        $this->mainIndex = route('purchaseList.index');
        $this->mainTitle = '采购对单';
		$this->viewPath = 'purchase.purchaseList.';
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
     * 采购条目对单
     *
     * @param $id
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
	
	public function update($id)
	{
		$data=request()->all();
		$this->model->purchaseListUpdate($id,$data);
        return redirect($this->mainIndex);		
	}
	
	public function updateActive($id)
	{
		$data=request()->all();
		$this->model->activeUpdate($id,$data);
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
	
	public function activeChange($id)
	{
		$res=$this->model->find($id);
		$second_supplier_id=$res->purchaseItem->second_supplier_id;
		$second_supplier=$this->supplier->find($second_supplier_id);
		$response = [
			'metas' => $this->metas(__FUNCTION__),
			'abnormal' => $res,
			'second_supplier'=>$second_supplier,
        ];
        return view($this->viewPath . 'changeActive', $response);
			
	}
}









