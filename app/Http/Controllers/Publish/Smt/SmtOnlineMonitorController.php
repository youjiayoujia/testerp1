<?php

namespace App\Http\Controllers\Publish\Smt;

use Channel;
use App\Http\Controllers\Controller;
use App\Models\Publish\Smt\smtProductSku;
use Illuminate\Support\Facades\Input;
use App\Models\Channel\AccountModel;
use App\Models\Publish\Smt\smtProductList;
use App\Models\ProductModel;


class SmtOnlineMonitorController extends Controller
{
    public function __construct(smtProductSku $smtProductSku){
        $this->viewPath = "publish.smt.";  
        $this->model = $smtProductSku;
        $this->mainIndex = route('smtMonitor.index');
    }
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->mainTitle='速卖通在线数量监控';
        $list = $this->model->whereHas('product',function($query){
            $query = $query->whereIn('productStatusType',['onSelling','offline','auditing','editingRequired']);
        });
        $response = [
            'metas' => $this->metas(__FUNCTION__),
            'data' => $this->autoList($this->model,$list),
            'mixedSearchFields' => $this->model->mixed_search,
        ];
        return view($this->viewPath . 'onlineMonitor', $response);
    }
    
    /**
     * 编辑商品单个SKU可售库存
     * @return \Illuminate\Http\RedirectResponse
     */
    public function editSingleSkuStock(){
        $data = array();
        $data = Input::get();
        $account_id = $data['account_id'];
        if($data['ipmSkuStock'] < 1){
            return redirect($this->mainIndex)->with('alert', $this->alert('danger',  '可售库存设置不正确 !'));
        }
        $account = AccountModel::findOrFail($account_id);
        $channel = Channel::driver($account->channel->driver, $account->api_config); 
        $result = $channel->editSingleSkuStock($data);
        if(array_key_exists('success',$result) && $result['success']){
            $where = ['productId' => $data['productId'], 'sku_active_id' => $data['skuId']];
            $updateData = ['ipmSkuStock' => $data['ipmSkuStock'],'updated' => date('Y:m:d H:i:s',time())];
            $this->model->where($where)->update($updateData);
            return redirect($this->mainIndex)->with('alert', $this->alert('success',  '产品'.$data['productId'].'的库存修改成功 !'));
        }else{
            return redirect($this->mainIndex)->with('alert', $this->alert('danger',  '库存修改失败:'.$result['error_message']));
        }
    }
    
    /**
     * 编辑商品单个SKU价格
     */
    public function editSingleSkuPrice(){
        $data = array();
        $data = Input::get();
        $account_id = $data['account_id'];
        $smtSkuCode = $data['smtSkuCode'];
        $setPriceType = $data['type'];
        $skuPrice = $data['skuPrice'];
        if(!$data['skuPrice']){
            return redirect($this->mainIndex)->with('alert', $this->alert('danger',  '价格不能为空 !'));
        }
        $account = AccountModel::findOrFail($account_id);
        $channel = Channel::driver($account->channel->driver, $account->api_config);
        $smtProductObj = new SmtProductsController();
        //获取商品的最新信息
        $last_product_Info = $channel->findAeProductById($data['productId']);
        if(array_key_exists('error_code', $last_product_Info)){
            return redirect($this->mainIndex)->with('alert', $this->alert('danger',  '获取商品信息失败:'.$last_product_Info['error_message']));
        }
        
        // 更新SKU的价格信息
        $aeopAeProductSKUs = array();
        $sku = is_array($smtSkuCode) ? $smtSkuCode : array($smtSkuCode);

        foreach($last_product_Info['aeopAeProductSKUs'] as $key => $v){
            if(in_array($v['skuCode'], $sku)){
                if($setPriceType == 'amount'){
                    $price = $v['skuPrice'] + $skuPrice;
                }else if($setPriceType == 'realprice'){
                    $price = $skuPrice;
                }else{
                    $price = $v['skuPrice'] * (1 + $skuPrice/100);
                }
                $v['skuPrice'] = sprintf('%.2f', round($price, 2));
                /*$skuCode = smtProductSku::where('smtSkuCode',$v['skuCode'])->first()->skuCode;
                if($skuCode){
                    $profitRate = $smtProductObj->_setProfitRate($data['productId'], $skuCode, $v['skuPrice']);
                }else {
                   $profitRate = 0; 
                }*/
            }
        }
        $data['skuPrice'] = $price;
    
        $result = $channel->editSingleSkuPrice($data);
        if(array_key_exists('success',$result) && $result['success']){
            $where = ['productId' => $data['productId'], 'sku_active_id' => $data['skuId']];
            $updateData = ['skuPrice' => $data['skuPrice'],'updated' => date('Y:m:d H:i:s',time())];
            $this->model->where($where)->update($updateData);
            return redirect($this->mainIndex)->with('alert', $this->alert('success',  '产品'.$data['productId'].'的价格修改成功 !'));
        }else{
            return redirect($this->mainIndex)->with('alert', $this->alert('danger',  '产品'.$data['productId'].'的价格修改失败:'.$result['error_message']));
        }
    }
    
    /**
     * 手动更新商品信息
     * @return unknown
     */
    public function manualUpdateProductInfo(){
        $productIdStr = Input::get('productIds');
        
        $productIds = explode(',', $productIdStr);
        $productIds = array_unique($productIds);
        foreach($productIds as $productId){                          
            $account_id = smtProductList::where('productId',$productId)->first()->token_id;
            
            $account = AccountModel::findOrFail($account_id);
            $channel = Channel::driver($account->channel->driver, $account->api_config);
            $result = $channel->findAeProductById($productId);
            if(array_key_exists('success', $result) && $result['success']){
               
                $product['product_url'] = 'http://www.aliexpress.com/item/-/' . $productId . '.html';
                $product['token_id'] = $account->id;
                $product['subject'] = array_key_exists('subject', $result) ? $result['subject'] : '';
                $product['productPrice'] = $result['productPrice'];
                $product['productStatusType'] = $result['productStatusType'];
                $product['ownerMemberId'] = $result['ownerMemberId'];
                $product['ownerMemberSeq'] = $result['ownerMemberSeq'];
                $product['wsOfflineDate'] = $this->parseDateString($result['wsOfflineDate']);
                $product['wsDisplay'] = array_key_exists('wsDisplay', $result) ? $result['wsDisplay'] : '';
                $product['groupId'] = array_key_exists('groupId', $result) ? $result['groupId'] : '';
                $product['categoryId'] = $result['categoryId'];
                $product['packageLength'] = $result['packageLength'];
                $product['packageWidth'] = $result['packageWidth'];
                $product['packageHeight'] = $result['packageHeight'];
                $product['grossWeight'] = $result['grossWeight'];
                $product['deliveryTime'] = $result['deliveryTime'];
                $product['wsValidNum'] = $result['wsValidNum'];
                $product['productMinPrice'] = $productItem['productMinPrice'];
                $product['productMaxPrice'] = $productItem['productMaxPrice'];
                $product['gmtCreate'] = $this->parseDateString($productItem['gmtCreate']);
                $product['gmtModified'] = $this->parseDateString($productItem['gmtModified']);
                $product['multiattribute'] = count($result['aeopAeProductSKUs']) > 1 ? 1 : 0;
            }
 
        }
        return $result;
       // return array('statue'=>true,'Msg'=>'更新成功!');
    }
    
    /**
     * 批量处理商品上下架
     * @return multitype:multitype:boolean string
     */
    public function ajaxOperateOnlineProductStatus(){
        $product_id_str = Input::get('productIds');
        $type = Input::get('type');
        $productArr = explode(',', $product_id_str);
        $productIds = array_unique($productArr);
        $response = array();
        foreach ($productIds as $productId){
            $smtProduct = smtProductList::where('productId',$productId)->first();
            $account = AccountModel::findOrFail($smtProduct->token_id);
            $smtApi = Channel::driver($account->channel->driver, $account->api_config);
            if($type == 'online'){
                $api = 'api.onlineAeProduct';
            }elseif($type == 'offline'){
                $api = 'api.offlineAeProduct';
            }else{
                $this->ajax_return('操作失败!',0);
            }
            $productId = $smtProduct->productId;
            $result= $smtApi->updateProductPublishState($api,$productId);
            if(array_key_exists('success',$result) && $result['success']){
                if($type == 'online'){
                    $data['productStatusType'] = 'onSelling';
                }else{
                    $data['productStatusType'] = 'offline';
                }
                $this->model->where('productId',$productId)->update($data);
                $response[] = array('Status'=> true, 'Msg' => '设置成功');
            }else{
                $response[] = array('Status' => false, 'Msg' => '产品'.$productId.'操作失败!失败原因：'.$result['error_message']);
            }
        }
        return $response;
    }
    
    /**
     * 批量修改商品在线库存
     * @return \Illuminate\Http\RedirectResponse
     */
    public function batchEditSkuStock(){
        $product_info = Input::get('products');
        $impSkuStock = Input::get('impSkuStock');
        $arr = explode(' ', $product_info); 
        $product_ids = $this->formatProductArray($arr);
 
        foreach($product_ids as $value){
            $productId = $value['product_id'];
            $smtSkuCode = array_unique($value['sku']);
            $product_skus = $this->model->whereIn('smtSkuCode', $smtSkuCode)->get()->toArray();
            $skuStocks = array();
            foreach($product_skus as  $row){
                $skuStocks[$row['sku_active_id']] = $impSkuStock;
            }   
            
            //获取渠道帐号信息
            $token_id = smtProductList::where('productId',$productId)->first()->token_id;         
            $account = AccountModel::findOrFail($token_id);
            $channel = Channel::driver($account->channel->driver, $account->api_config);
            
            $data['skuStocks'] = json_encode($skuStocks);
            $data['productId'] = $productId;
            $api = 'api.editMutilpleSkuStocks';          
            $result = $channel->getJsonDataUsePostMethod($api,$data);         
            $result = json_decode($result,true);
            if(array_key_exists('success',$result) && $result['success']){
                foreach ($product_skus as $item){
                    $where = ['productId' => $data['productId'], 'sku_active_id' => $item['sku_active_id']];
                    $updateData = ['ipmSkuStock' => $impSkuStock];
                    $this->model->where($where)->update($updateData);
                }                
                $flag = true;
            }else{
                return redirect($this->mainIndex)->with('alert', $this->alert('danger',  '库存修改失败:'.$result['error_message']));
            }
        
        }                 
        
        return redirect($this->mainIndex)->with('alert', $this->alert('success',  '批量操作成功 !'));
    }
    
    /**
     * 批量修改商品价格
     */
    public function batchEditSkuPrice(){
        $product_info = Input::get('products');
        $skuPrice = Input::get('skuPrice');
        $setPriceType = Input::get('type');
        $arr = explode(' ', $product_info);
        foreach ($arr as  $item){
            $singleSkuArr = explode(',', $item);
            $productId = $singleSkuArr[0];
            $smtSkuCode = $singleSkuArr[1];
            
            $skuInfo = $this->model->where('smtSkuCode',$smtSkuCode)->first();
            $account_id = $skuInfo->product->token_id;
            
            $account = AccountModel::findOrFail($account_id);
            $channel = Channel::driver($account->channel->driver, $account->api_config);
            
            //获取商品的最新信息
            $last_product_Info = $channel->findAeProductById($productId);
            if(array_key_exists('error_code', $last_product_Info)){
                return redirect($this->mainIndex)->with('alert', $this->alert('danger',  '获取商品信息失败:'.$last_product_Info['error_message']));
            }
            
            // 更新SKU的价格信息
            $aeopAeProductSKUs = array();
            $sku = is_array($smtSkuCode) ? $smtSkuCode : array($smtSkuCode);
            
            foreach($last_product_Info['aeopAeProductSKUs'] as $key => $v){
                if(in_array($v['skuCode'], $sku)){
                    if($setPriceType == 'amount'){
                        $price = $v['skuPrice'] + $skuPrice;
                    }else if($setPriceType == 'realprice'){
                        $price = $skuPrice;
                    }else{
                        $price = $v['skuPrice'] * (1 + $skuPrice/100);
                    }
                    $v['skuPrice'] = sprintf('%.2f', round($price, 2));
                }
            }
            $data['skuPrice'] = $price;
            $data['skuId'] = $skuInfo->sku_active_id;
            $data['productId'] = $productId;
            $result = $channel->editSingleSkuPrice($data);
            if(array_key_exists('success',$result) && $result['success']){
                $where = ['productId' => $data['productId'], 'sku_active_id' => $data['skuId']];
                $updateData = ['skuPrice' => $data['skuPrice'],'updated' => date('Y:m:d H:i:s',time())];
                $this->model->where($where)->update($updateData);                
            }else{
                return redirect($this->mainIndex)->with('alert', $this->alert('danger',  '的价格修改失败:'.$result['error_message']));
            }
        }
        return redirect($this->mainIndex)->with('alert', $this->alert('success',  '操作成功 !'));
    }
    
    public function ajaxOperateProductSkuStockStatus(){
        $product_id_str = Input::get('productIds');
        $type = Input::get('type');
        $productArr = explode(' ', $product_id_str);
        foreach($productArr as $product){
            $tmp = explode(',', $product);
            $productId = $tmp[0];
            $smtSkuCode = $tmp[1];
            
            $skuInfo = $this->model->where('smtSkuCode',$smtSkuCode)->first();
            $account_id = $skuInfo->product->token_id;            
            $account = AccountModel::findOrFail($account_id);
            $channel = Channel::driver($account->channel->driver, $account->api_config);
            
            $data['skuId'] = $skuInfo->sku_active_id;
            $data['productId'] = $productId;
            if($type == 'No'){
                $data['impSkuStock'] = 0;
            }else{
                $data['impSkuStock'] = 999;
            }
            $result = $channel->editSingleSkuStock($data);
            if(array_key_exists('success',$result) && $result['success']){
                $where = ['productId' => $data['productId'], 'sku_active_id' => $data['skuId']];
                $updateData = ['ipmSkuStock' => $data['ipmSkuStock'],'updated' => date('Y:m:d H:i:s',time())];
                $this->model->where($where)->update($updateData);
                $response[] = array('Status'=> true, 'Msg' => '设置成功');
            }else{
                $response[] = array('Status' => false, 'Msg' => '产品'.$productId.'操作失败!失败原因：'.$result['error_message']);
            }
        }
            return $response;
    }
    
    /**
     * 对传入的数组进行格式化
     */
    public function formatProductArray($product_id){
        foreach ($product_id as $value) {
            list($v[0], $v[1]) = explode(',', $value);
            $p_ids[$v[0]][] = $v[1];
        }
        foreach ($p_ids as $key => $value) {
            $product_ids[] = array('product_id' => $key, 'sku' => $value);
        }
        
        return $product_ids;
    }
   
}
