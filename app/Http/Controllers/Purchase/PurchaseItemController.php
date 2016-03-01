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
        $this->mainTitle = '采购条目';
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
	

}









