<?php
namespace App\Modules\Logistics\Adapter;


use App\Models\PackageModel;
class FpxAdapter extends BasicAdapter{
    public function getTracking($package)
    {
        $this->soapClient = new \SoapClient($this->config['url'], array('encoding'=>'UTF-8'));
        
        $declave_values = $package->order->amount / $package->order->rate;
        $unitPrice = ($declave_values > 30) ? 30 : ceil($declave_values); 
        foreach($package->items as $item){
            $products_info = array(array(
                "eName" => $item->item->product->declared_en,   //海关申报英文品名
                "name" => $item->item->product->declared_cn,    //海关申报中文品名
                "unitPrice" => $unitPrice,                      //单价  
            ));   
        }
        list($name,$productCode) =  explode(',',$package->logistics->type);
        $arrs = array(    
            "city" => $package->shipping_city,//城市 【***】
            "consigneeEmail" => $package->order->email,//收件人Email
            "consigneeName" => $package->shipping_firstname . " " . $package->shipping_lastname,//收件人姓名【***】
            "consigneePostCode" => $package->shipping_zipcode,//收件人邮编
            "consigneeTelephone" => $package->shipping_phone,//收件人电话号码
            "destinationCountryCode" => $package->shipping_country,//目的国家二字代码，参照国家代码表
            "initialCountryCode" => 'CN',               //起运国家二字代码，参照国家代码表【***】
            "orderNo" => 'LME' . $package->order_id,    //客户订单号码，由客户自己定义【***】
            "productCode" => $productCode,                      //产品代码，指DHL、新加坡小包挂号、联邮通挂号等，参照产品代码表 【***】    不确定字段在哪个表中,如何获取，暂时固定
            "returnSign" => 'Y',                        //小包退件标识 Y: 发件人要求退回 N: 无须退回(默认)
            "stateOrProvince" => $package->shipping_state,//州  /  省 【***】
            "street" => $package->shipping_address .' '. $package->shipping_address1,//街道【***】
            'declareInvoice' => $products_info
        );     
        
        $params  = array();
        $params['arg0'] = $this->config['key'];   //4px Token
        $params['arg1'] = $arrs;
        
        $response = $this->soapClient->createAndPreAlertOrderService($params);
        if($response['ack'] == 'success'){
            $data = ['tracking_no' => $response['trackingNumber']];
            PackageModel::where('id',$package->id)->update($data);
            $result = [
                'code' => 'success',
                'result' => $response['trackingNumber']
            ];            
        }else{
            $result = [
                'code' => 'error',
                'result' => 'error description.'
            ];
        }
        return $result;
    }
}