<?php

namespace App\Http\Controllers\Publish\Smt;

use Channel;
use App\Http\Controllers\Controller;
use App\Models\Publish\Smt\smtProductSku;
use Illuminate\Support\Facades\Input;
use App\Models\Channel\AccountModel;
use App\Models\Publish\Smt\smtProductList;


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
        $response = [
            'metas' => $this->metas(__FUNCTION__),
            'data' => $this->autoList($this->model),
            'mixedSearchFields' => $this->model->mixed_search,
        ];
        return view($this->viewPath . 'onlineMonitor', $response);
    }

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
        
        //获取商品的最新信息
        $last_product_Info = $channel->findAeProductById($data['productId']);
        if(array_key_exists('error_code', $last_product_Info)){
            return redirect($this->mainIndex)->with('alert', $this->alert('danger',  '获取商品信息失败:'.$result['error_message']));
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
        $result = $channel->editSingleSkuPrice($data);
        if(array_key_exists('success',$result) && $result['success']){
            $where = ['productId' => $data['productId'], 'sku_active_id' => $data['skuId']];
            $updateData = ['skuPrice' => $data['skuPrice'],'updated' => date('Y:m:d H:i:s',time())];
            $this->model->where($where)->update($updateData);
            return redirect($this->mainIndex)->with('alert', $this->alert('success',  '产品'.$data['productId'].'的价格修改成功 !'));
        }else{
            return redirect($this->mainIndex)->with('alert', $this->alert('danger',  '的价格修改失败:'.$result['error_message']));
        }
    }
    
    public function manualUpdateProductInfo(){
        $productIdStr = Input::get('productIds');
        
        $productIds = explode(' ', $productIdStr);

        foreach($productIds as $item){
            $productInfo = explode(',', $item);
            $productId = $productInfo[0];
            $smtSkuCode = $productInfo[1];
            $account_id = smtProductList::where('productId',$productId)->first()->token_id;
            
            $account = AccountModel::findOrFail($account_id);
            $channel = Channel::driver($account->channel->driver, $account->api_config);
            $result = $channel->findAeProductById($productId);
 
        }
        return $result;
       // return array('statue'=>true,'Msg'=>'更新成功!');
    }
    
}
