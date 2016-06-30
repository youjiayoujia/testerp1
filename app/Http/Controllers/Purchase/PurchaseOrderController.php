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
use App\Models\Purchase\PurchaseItemArrivalLogModel;
use App\Models\WarehouseModel;
use App\Models\ItemModel;
use App\Models\Stock\InModel;
use App\Models\Product\SupplierModel;
use App\Models\Purchase\PurchasePostageModel;
use Tool;

class PurchaseOrderController extends Controller
{

    public function __construct(PurchaseOrderModel $purchaseOrder)
    {
        $this->model = $purchaseOrder;
        $this->mainIndex = route('purchaseOrder.index');
        $this->mainTitle = '采购单';
        $this->viewPath = 'purchase.purchaseOrder.';
    }
    
    
    public function index()
    {
        $response = [
            'metas' => $this->metas(__FUNCTION__),
            'data' => $this->autoList($this->model),
            'mixedSearchFields' => $this->model->mixed_search,
        ];
        foreach($response['data'] as $key=>$vo){
            $response['data'][$key]['purchase_items']=PurchaseItemModel::where('purchase_order_id',$vo->id)->get();
            $response['data'][$key]['purchase_post_num']=PurchasePostageModel::where('purchase_order_id',$vo->id)->sum('postage');
            $response['data'][$key]['purchase_post']=PurchasePostageModel::where('purchase_order_id',$vo->id)->first();
            foreach($response['data'][$key]['purchase_items'] as $v){
            $response['data'][$key]['sum_purchase_num'] +=$v->purchase_num;
            $response['data'][$key]['sum_arrival_num'] +=$v->arrival_num;
            $response['data'][$key]['sum_storage_qty'] +=$v->storage_qty;
            $response['data'][$key]['sum_purchase_account'] += ($v->purchase_num * $v->purchase_cost);
            $response['data'][$key]['sum_purchase_storage_account'] +=  ($v->storage_qty * $v->purchase_cost);
            }
            /*$response['data'][$key]['sum_purchase_num']=PurchaseItemModel::where('purchase_order_id',$vo->id)->sum('purchase_num');
            $response['data'][$key]['sum_arrival_num']=PurchaseItemModel::where('purchase_order_id',$vo->id)->sum('arrival_num');
            $response['data'][$key]['sum_storage_qty']=PurchaseItemModel::where('purchase_order_id',$vo->id)->sum('storage_qty');*/
            }
        return view($this->viewPath . 'index', $response);
    }
    
    
    
    /**
     * 采购页面
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
            'purchaseItems'=>PurchaseItemModel::where('purchase_order_id',$id)->get(),
            'purchasePostage'=>PurchasePostageModel::where('purchase_order_id',$id)->get(),
            'purchaseSumPostage'=>PurchasePostageModel::where('purchase_order_id',$id)->sum('postage'),
            'current'=>count(PurchasePostageModel::where('purchase_order_id',$id)->get()->toArray()),
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
            'purchaseItems'=>PurchaseItemModel::where('purchase_order_id',$id)->get(),
            'purchaseItemsNum'=>PurchaseItemModel::where('purchase_order_id',$id)->sum('purchase_num'),
            'purchaseItemsArrivalNum'=>PurchaseItemModel::where('purchase_order_id',$id)->sum('arrival_num'),
            'storage_qty_sum'=>PurchaseItemModel::where('purchase_order_id',$id)->sum('storage_qty'),
            'postage'=>PurchasePostageModel::where('purchase_order_id',$id)->sum('postage')
        ];
        $response['purchaseCost']=0;
        $response['storageCost']=0;
        foreach($response['purchaseItems'] as  $key=>$v){
            $response['purchaseCost'] +=$v->purchase_num * $v->purchase_cost;
            $response['storageCost'] +=$v->storage_qty * $v->purchase_cost;
            }
        return view($this->viewPath . 'show', $response);
    }
    
    /**
     * 更新采购单
     *
     * @param $id
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    
    public function update($id)
    {
        $model=$this->model->find($id);
        if ($model->status ==4 ) {
            return redirect($this->mainIndex)->with('alert', $this->alert('danger', $this->mainTitle . '该采购单已完成.'));
        }
        if ($model->status ==5 ) {
            return redirect($this->mainIndex)->with('alert', $this->alert('danger', $this->mainTitle . '该采购单已取消.'));
        }

        $data=request()->all();
        if(isset($data['arr'])){
            if(isset($data['post'])){
                $post="";
                if($model->status <4 && $model->status >0){
                    foreach($data['post'] as $post_value){
                        $post_array['purchase_order_id'] = $data['purchase_order_id'];
                        $post_array['post_coding'] = $post_value['post_coding'];
                        $post_array['postage'] = $post_value['postage'];
                        $post_array['user_id'] = request()->user()->id;
                        if(array_key_exists('id',$post_value)){
                            PurchasePostageModel::where('id',$post_value['id'])->update($post_array);
                        }else{
                            PurchasePostageModel::create($post_array);
                        }
                        
                        
                    }
                }
            }
                
            foreach($data['arr'] as $k=>$v){
                if($v['id']){
                    $purchaseItem=PurchaseItemModel::find($v['id']);
                    $itemPurchasePrice=$purchaseItem->item->purchase_price;
                    $purchase_num=$purchaseItem->purchase_num;
                    foreach($v as $key=>$vo){
                        $item[$key]=$vo;    
                    }
                    if($v['active']>0){
                        $item['active_status']=1;
                    }
                    if($item['purchase_cost'] >0.6*$itemPurchasePrice && $item['purchase_cost'] <1.3*$itemPurchasePrice ){
                        $item['costExamineStatus']=2;
                        ItemModel::where('sku',$purchaseItem->sku)->update(['purchase_price'=>$item['purchase_cost']]); 
                    }else{
                        $item['costExamineStatus']=0;   
                    }
                    /*if($item['status']>0){
                        $data['status']=1;
                    }*/
                    if($purchaseItem->purchaseOrder->examineStatus ==1){
                        if($purchaseItem->status < 4){
                        $data['examineStatus']=2;
                        }
                    }
                    $item['start_buying_time']=date('Y-m-d h:i:s',time());
                    if($purchaseItem->status < 4){
                    $purchaseItem->update($item);
                    }
                    $data['total_purchase_cost'] +=$v['purchase_cost']*$purchase_num;
                    unset($item);
                }
            }
        }
        $num=PurchaseItemModel::where('purchase_order_id',$id)->where('costExamineStatus','<>',2)->count();
        if($num ==0){
            $data['costExamineStatus']=2;
        }
        $data['start_buying_time']=date('Y-m-d h:i:s',time());  
        $model->update($data);
        return redirect( route('purchaseOrder.edit', $id));     
    }
    
    /**
     * 导出采购单
     *
     * @param $id
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function purchaseOrdersOut()
    {
        $response = [
            'metas' => $this->metas(__FUNCTION__),
        ];
        return view($this->viewPath.'excelOut',$response);  
    }

     /**
     * 审核采购单
     *
     * @param $id
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    
    public function changeExamineStatus($id,$examineStatus)
    {
        $model=$this->model->find($id);
        if (!$model) {
            return redirect($this->mainIndex)->with('alert', $this->alert('danger', $this->mainTitle . '不存在.'));
        }
        $data['examineStatus']=$examineStatus;
        $data['status'] = 1;
        $model->update($data);
        return redirect( route('purchaseOrder.edit', $id));
    }
    /**
     * 导出3天未到货采购单
     *
     * 
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */ 
    public function excelOrderOut($num){
        if($num==0){
            $this->model->allPurchaseExcelOut();    
        }elseif($num==3){
            $this->model->noArrivalOut();
            }
    }
    
    /**
     * 取消采购单
     *
     * @param $id
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */ 
    public function cancelOrder($id)
    {   
        $num=purchaseItemModel::where('purchase_order_id',$id)->where('status','>',1)->count();
        if($num>0){
            return redirect($this->mainIndex)->with('alert', $this->alert('danger', $this->mainTitle . '此采购单不能取消.'));
            }
        $purchaseItem=PurchaseItemModel::where('purchase_order_id',$id)->update(['status'=>5]);
        $this->model->find($id)->update(['status'=>5,'examineStatus'=>3]);
        return redirect($this->mainIndex)->with('alert', $this->alert('success', $this->mainTitle . '改采购单已退回'));    
    }
    
    /**
     * ajax 新增物流单号物流费
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View|null
     */
    public function ajaxPostAdd()
    {
        if (request()->input('current')) {
            $current = request()->input('current');
            $response = [
                'current' => $current,
            ];

            return view($this->viewPath . 'add', $response);
        }
        return null;
    }
    /**
     * 新增产品条目
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View|null
     */
    
    public function addItem($id)
    {
        $response = [
            'metas' => $this->metas(__FUNCTION__),
            'purchase_order_id'=>$id,
        ];
        return view($this->viewPath.'addItem',$response);
    }
    /**
     * 创建采购条目
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View|null
     */
    public function createItem($id){
        $data=request()->all();
        $model=$this->model->find($id);
        $num=PurchaseItemModel::where('active_status','>',0)->where('sku',$data['sku'])->count();
        $Inum=ItemModel::where('sku',$data['sku'])->where('is_available','<>',1)->where('status',"selling")->count();
        $item=ItemModel::where('sku',$data['sku'])->where('is_available',1)->where('status',"selling")->first();
        if($num > 0 || $Inum > 0){
            return redirect(route('purchaseOrder.edit', $id))->with('alert', $this->alert('danger', $this->mainTitle . '此Item存在异常不能添加进此采购单.'));
        }
        if($model->close_status == 1){
            return redirect(route('purchaseOrder.edit', $id))->with('alert', $this->alert('danger', $this->mainTitle . '该采购单已结算，不能新增Item.'));
            }
        $data['lack_num']=$data['purchase_num'];
        $data['warehouse_id']=$model->warehouse_id ? $model->warehouse_id : 0;
        $data['supplier_id']=$item->supplier_id ? $item->supplier_id : 0;
        $data['purchase_order_id']=$id;
        PurchaseItemModel::create($data);
        if($model->examineStatus >0){
        $model->update(['examineStatus'=>2]);
        }
        return redirect( route('purchaseOrder.edit', $id)); 
        }
    /**
    * 添加报等时间页面
    *
    * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View|null
    */
    public function updateWaitTime($id){
        $response = [
                'metas' => $this->metas(__FUNCTION__),
                'purchase_item_id'=>$id,
            ];
        return view($this->viewPath.'waitTime',$response);  
    }
    /**
    * 添加报等时间
    *
    * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View|null
    */
    public function updateItemWaitTime($id){
        $data=request()->all();
        $purchaseItem=purchaseItemModel::find($id);
        purchaseItemModel::where('id',$id)->update(['wait_time'=>$data['wait_time'],'wait_remark'=>$data['wait_remark']]);
        return redirect( route('purchaseOrder.edit', $purchaseItem->purchase_order_id));    
        }
    /**
    * 打印
    *
    * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View|null
    */
    public function printOrder($id){
        $model=$this->model->find($id);
        if (!$model) {
            return redirect($this->mainIndex)->with('alert', $this->alert('danger', $this->mainTitle . '不存在.'));
        }
        $response = [
            'metas' => $this->metas(__FUNCTION__),
            'model' => $model,
            'id' => $id,
            'purchaseItems'=>PurchaseItemModel::where('purchase_order_id',$id)->get(),
            'purchase_num_sum'=>PurchaseItemModel::where('purchase_order_id',$id)->sum('purchase_num'),
            'storage_qty_sum'=>PurchaseItemModel::where('purchase_order_id',$id)->sum('storage_qty'),
            'postage_sum'=>PurchasePostageModel::where('purchase_order_id',$id)->sum('postage'),
        ];
        $purchaseAccount='';
        foreach($response['purchaseItems'] as $key=>$v){
            $purchaseAccount +=$v->purchase_num * $v->purchase_cost;
            }
            $response['purchaseAccount']=$purchaseAccount;
        return view($this->viewPath . 'printOrder', $response);
    }

    /**
    * 修改打印状态
    *
    * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View|null
    */
    public function changePrintStatus(){
        $id = request()->input('id');
        $this->model->find($id)->update(['print_status'=>1]);
    }

    /**
    * 收货节面打印采购条目
    *
    * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View|null
    */
    public function printpo(){
        $id = request()->input('id');
        echo Tool::barcodePrint($id);
    }

    /**
    * 收货节面打印采购条目
    *
    * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View|null
    */
    public function payOrder($id){
        $model=$this->model->find($id);
        $model->update(['close_status'=>1]);
        return redirect($this->mainIndex)->with('alert', $this->alert('success', $this->mainTitle . "已付款"));
    }
    
    
    public function write_off($id){
        $off = request()->input("off");
        if($off==1){
            $this->model->find($id)->update(['write_off'=>$off+1,'status'=>4]);
            $remark = "核销成功";
        }else{
            $this->model->find($id)->update(['write_off'=>$off+1]);
            $remark = "待核销成功";
        }
        
        return redirect($this->mainIndex)->with('alert', $this->alert('success', $this->mainTitle . $remark));
    }

    public function addPost($id){
        $data=request()->all();
        $model=$this->model->find($id);
        foreach($data['post'] as $v){
            PurchasePostageModel::create(['purchase_order_id'=>$id,'post_coding'=>$v['post_coding'],'postage'=>$v['postage']]);
        }
        return redirect($this->mainIndex)->with('alert', $this->alert('success', $this->mainTitle . '成功添加运单号'));    
    }

    public function recieve(){
        $response = [
            'metas' => $this->metas(__FUNCTION__),
        ];
        $response['metas']['title']='采购收货';
        return view($this->viewPath . 'recieve', $response);
    }

    public function ajaxRecieve(){
        $id = request()->input('id');
        $purchase_order = $this->model->find($id);
        $response = [
                'purchase_order' => $purchase_order,
                'id'=>$id,
            ];
        return view($this->viewPath . 'recieveList', $response);
    }

    public function trackingNoSearch(){
        $data = request()->all();
        if(count($data)==0){
            $result = PurchasePostageModel::all();
        }else{
            $result = new PurchasePostageModel();
            if($data['po_id']!=''){
                $result = $result->where("purchase_order_id",$data["po_id"]);
            }
            if($data['status']!=''){
               $data['status']?$result = $result->where("purchase_order_id",'!=',''):$result->where("purchase_order_id",'');
            }
            if($data['trackingNo']!=''){
                $result = $result->where("post_coding",$data["trackingNo"]);
            }
            $result = $result->get();
        }
        
        $response = [
            'result' => $result,
        ];
        
        return view($this->viewPath . 'scanList', $response);
    }

    public function updateArriveNum(){
        $data = request()->input("data");
        $p_id = request()->input("p_id");
        if($data!=''){
            $data = substr($data, 0,strlen($data)-1);
            $arr = explode(',', $data);
            foreach ($arr as $value) {
                $update_data = explode(':', $value);
                $purchase_item = PurchaseItemModel::find($update_data[0]);
                
                if($purchase_item->arrival_num!=$purchase_item->purchase_num){
                    $filed['purchase_item_id'] = $purchase_item['id'];
                    $filed['sku'] = $purchase_item['sku'];
                    $filed['arrival_num'] = $purchase_item['arrival_num']+$update_data[1]>10?10:$purchase_item['arrival_num']+$update_data[1];
                    $filed['lack_num'] =  $purchase_item['purchase_num']-$filed['arrival_num']<0?0:$purchase_item['purchase_num']-$filed['arrival_num'];
                    $filed['arrival_time'] = date('Y-m-d H:i:s',time());
                    $filed['status'] = 2;
                    $purchase_item->update($filed);
                    $filed['arrival_num'] = $update_data[1];
                    PurchaseItemArrivalLogModel::create($filed);
                }
                
            } 
        }else{
            $purchaseOrderModel = $this->model->find($p_id);
            foreach($purchaseOrderModel->purchaseItem as $p_item){
                if($p_item->purchase_num!=$p_item->arrival_num){
                    $p_item->update(['arrival_num'=>$p_item->purchase_num,'lack_num'=>0]);
                    $filed['purchase_item_id'] = $p_item->id;
                    $filed['sku'] = $p_item->sku;
                    $filed['status'] =2;
                    $filed['arrival_num'] = $p_item->lack_num;
                    PurchaseItemArrivalLogModel::create($filed);
                }
            }
        }
        
        echo json_encode($p_id);
    }

    public function inWarehouse(){
        $response = [
            'metas' => $this->metas(__FUNCTION__),
        ];
        $response['metas']['title']='采购入库';
        return view($this->viewPath . 'inWarehouse', $response);
    }

    public function ajaxInWarehouse(){
        $id = request()->input('id');
        $purchase_order = $this->model->find($id);
        $response = [
                'purchase_order' => $purchase_order,
                'id'=>$id,
            ];
        return view($this->viewPath . 'inWarehouseList', $response);
    }

    /**
    * 入库
    *
    * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View|null
    */
    public function updateArriveLog(){
        $data = request()->input("data");
        $p_id = request()->input("p_id");
        $data = substr($data, 0,strlen($data)-1);
        $arr = explode(',', $data);
        
        foreach ($arr as $value) {
            $update_data = explode(':', $value);
            $arrivel_log = PurchaseItemArrivalLogModel::find($update_data[0]);
            $purchase_item = $arrivel_log->purchaseItem;
            //print_r($purchase_item->item->sku);exit;
            if($purchase_item->item->warehouse_position==''){
                echo json_encode($purchase_item->item->sku);exit;
            }else{
                $filed['good_num'] = $update_data[1]>$purchase_item->arrival_num?$purchase_item->arrival_num:$update_data[1];
                $filed['bad_num'] =  $arrivel_log->arrival_num-$update_data[1];
                $filed['quality_time'] = date('Y-m-d H:i:s',time());
                
                $arrivel_log->update($filed);
                //purchaseitem
                $datas['status'] = 3;
                $datas['storage_qty'] = $purchase_item->storage_qty+$filed['good_num'];
                $datas['unqualified_qty'] = $purchase_item->unqualified_qty+$filed['bad_num'];
                if($datas['storage_qty']>=$purchase_item->purchase_num){
                    $datas['status'] = 4;
                }
                //print_r($datas);
                $purchase_item->update($datas);
                $purchase_item->item->in($purchase_item->item->warehouse_position,$filed['good_num'],$filed['good_num']*$purchase_item->purchase_cost,'PURCHASE',$purchase_item->purchaseOrder->id);
            }       
        }
        
        $p_status = 4;
        $purchasrOrder = $this->model->find($p_id);
        foreach($purchasrOrder->purchaseItem as $p_item){
            if($p_item->status!=4){
                $p_status = 3;
            }
        }
        $purchasrOrder->update(['status'=>$p_status]);
        $p_id = (int)$p_id;
        echo json_encode($p_id);
    }
        
}








