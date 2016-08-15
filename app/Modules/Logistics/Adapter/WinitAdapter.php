<?php
namespace App\Modules\Logistics\Adapter;


use App\Models\PackageModel;
class WinitAdapter extends BasicAdapter
{
    protected $shipperAddrCode =array(
        999 =>'A00004',
        1000=>'SLME003',
        5 => 'SLME003',
    );
    
    protected $winitProductCode=array(
        '999' =>'WP-MYP002',
    );
    
    //YW10000008
    protected $warehouseCode =array(
        'WP-HKP101'=> 'YW10000008',
    );
    
    protected $winitProductCodeName =array(
        'WP-HKP001'=>'万邑邮选-香港渠道（平邮）',
        'WP-HKP002'=>'万邑邮选-香港渠道（挂号）',
        'WP-MYP001'=>'万邑邮选-马来西亚渠道（平邮）',
        'WP-EEP002'=>'万邑邮选-爱沙尼亚渠道（平邮）',
        'WP-EEP001'=>'万邑邮选-爱沙尼亚渠道（挂号）',
        'WP-SGP003'=>'万邑邮选-新加坡渠道（挂号）',
        'WP-SGP004'=>'万邑邮选-新加坡渠道（平邮）',
        'WP-NLP001'=>'万邑邮选-荷兰渠道（挂号）-含电',
        'WP-NLP011'=>'万邑邮选-荷兰渠道（挂号）-不含电',
    
        'WP-NLP002'=>'万邑邮选-荷兰渠道（平邮）-含电',
        'WP-NLP012'=>'万邑邮选-荷兰渠道（平邮）-不含电',
        'WP-CNP007'=>'万邑邮选-普通渠道（挂号）-北京',
        'WP-CNP004'=>'万邑邮选-普通渠道（平邮）-北京',
    
        'WP-SRP001'=>'万邑邮选-俄罗斯SPSR渠道（挂号）',
        'WP-FIP001'=>'万邑邮选-芬兰渠道（挂号）',
        'WP-DEP001'=>'万邑邮选-德国渠道（挂号）',
        'WP-DEP002'=>'万邑邮选-德国渠道（平邮）',
    
        'WP-CNP005'=>'万邑邮选-普通渠道（挂号）-上海',
        'WP-CNP006'=>'万邑邮选-普通渠道（平邮）-上海',
        'WP-HKP101'=>'万邑邮选-香港渠道（平邮）-eBay IDSE',
        'WP-MYP101'=>'万邑邮选-马来西亚渠道（平邮）-ebay IDSE',
    
        'WP-NLP101'=>'万邑邮选-荷兰渠道（平邮）-ebay IDSE-含电',
        'WP-NLP102'=>'万邑邮选-荷兰渠道（平邮） -eBay IDSE-不含电',
        'WP-DEP102'=>'万邑邮选-德国渠道（平邮香港）-ebay IDSE',
        'WP-DEP103'=>'万邑邮选-德国渠道（平邮上海）-ebay IDSE',
    );
    
    public $token = '069C80E8D3E89D0618A98CE62DDE824A';
    public function getTracking($package)
    {
        $this->config = $package->logistics->api_config;
        
        $creatOrder = array();
        $creatOrder['buyerAddress1'] = $package->shipping_address;
        $creatOrder['buyerAddress2'] = $package->shipping_address1;
        $creatOrder['buyerCity'] = $package->shipping_city;
        $creatOrder['buyerContactNo'] = $package->shipping_phone;
        $creatOrder['buyerCountry'] = $package->shipping_country;
        $creatOrder['buyerEmail'] = $package->order->email;
        $creatOrder['buyerHouseNo'] = "";
        $creatOrder['buyerName'] = $package->shipping_firstname . " " . $package->shipping_lastname;
        $creatOrder['buyerState'] = $package->shipping_state;
        $creatOrder['buyerZipCode'] = $package->shipping_zipcode;
        $creatOrder['dispatchType'] = 'P';
        $creatOrder['ebaySellerId'] = '';
        
        $product_last = array();
        $product_detail = array();
        //$product_last['packageDesc'] = 
        $product_last['height'] = $package->height;
        $product_last['length'] = $package->length;
        $product_last['width'] = $package->width;        
        $product_last['weight'] = $package->weight;
        
        foreach ($package->items as $key => $item) {                   
            if ($key == 0) {
                $product_detail['declareNameCn'] = $item->item->product->declared_cn;
                $product_detail['declareNameEn'] = $item->item->product->declared_en;
                $product_detail['declareValue'] = $item->item->product->declared_value;
                $product_detail['itemID'] = $item->orderItem->item_id;    //条目ID（eBay订单必填）  字段不确定，待确认
                $product_detail['transactionID'] = $item->orderItem->transaction_id; //交易ID（eBay订单必填） 不确定字段数据相关联的表
            }
            $product_last['merchandiseList'][] = $product_detail;
        }
        
        ksort($product_last);
        $creatOrder['packageList'][] = $product_last;               //包裹列表
        $creatOrder['pickUpCode'] = $package->picklist->picknum;    //捡货条码
        $creatOrder['refNo'] = $package->order->ordernum;          //卖家订单号    字段不确定，待确认

        $creatOrder['shipperAddrCode'] = $this->shipperAddrCode[$package->warehouse_id];        
        $creatOrder['warehouseCode'] = $this->warehouseCode['WP-HKP101'];
        $creatOrder['winitProductCode'] = 'WP-HKP101';
       
        $postData = array();
        $postData['action'] = 'isp.order.createOrder';  
        $postData['app_key'] = $this->config['userId'];
        $postData['data'] = $creatOrder;
        $postData['format'] = 'json';
        $postData['language'] = 'zh_CN';
        $post_array['platform'] ="SELLERERP";        
        $this->getSign($this->token,$postData['action'],$postData['data']);
        $postData['sign'] =$this->sign;
        $postData['sign_method'] ="md5";
        $postData['timestamp'] =date('Y-m-d H:i:s');
        $postData['version'] ="1.0";
        $url = $this->config["url"];      
        $headers = array("application/x-www-form-urlencoded; charset=gb2312");
        $result = $this->curlPost($url, json_encode($postData,JSON_UNESCAPED_UNICODE),$headers);
        dd($result);
        if(isset($result['code'])&&($result['code']==0)&&($result['msg']=='操作成功'))
        {   
            $data = ['tracking_no' => $result['data']['orderNo'] ];
            PackageModel::where('id',$package->id)->update($data);           
        
        }else{        
          
        }
    }
    
        
    public function getToken(){    
        $code = array();
        $code['action'] = "getToken";
        $code['data']['userName'] = $this->config['userId'];
        $code['data']['passWord'] = $this->config['userPassword'];
        $url = 'http://erp.demo.winit.com.cn/ADInterface/api';
        return $this->curlPost($url,json_encode($code));        
    }
    
    public function getSign($token,$action,$data=''){
        $time =date('Y-m-d H:i:s');
        $string =$token.'action'.$action.'app_key'.$this->config['userId'].'data'.(string)json_encode($data,JSON_UNESCAPED_UNICODE).'formatjsonplatformsign_methodmd5timestamp'.$time.'version1.0'.$token;   
        $sign =strtoupper(md5($string));
        $this->sign = $sign;
    
    }
    
}

?>