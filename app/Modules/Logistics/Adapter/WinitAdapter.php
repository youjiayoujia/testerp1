<?php
namespace App\Modules\Logistics\Adapter;


use App\Models\PackageModel;
class WinitAdapter extends BasicAdapter
{
    private $server_url ;
    private $userName ;
    private $passWord ;
    private $token;
    private $sign;
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
    
    public function __construct($config){
        $this->userName = $config['userId'];
        $this->passWord = $config['userPassword'];
        $this->token = $config['key'];
        $this->server_url = $config['url'];
    }
    
    public function getTracking($package)
    {              
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
    
        if($package->logistics_id == 524){
            $new_warehouseCode='YW10000012';//此渠道发金华仓
            $new_shipperAddrCode = 'YWSLME';
        }else{
            $new_warehouseCode=$this->warehouseCode[$this->winitProductCode];
            $new_shipperAddrCode=$this->shipperAddrCode[$package->warehouse_id];
        }
        /*$creatOrder['shipperAddrCode'] = $this->shipperAddrCode[$package->warehouse_id];        
        $creatOrder['warehouseCode'] = $this->warehouseCode['WP-HKP101']
        $creatOrder['winitProductCode'] = 'WP-HKP101';*/
        
        $creatOrder['shipperAddrCode'] = $new_shipperAddrCode;        
        $creatOrder['warehouseCode'] = $new_warehouseCode;
        $creatOrder['winitProductCode'] = $this->winitProductCode;
        
        //$this->setApi($package->warehouse_id);
        $result = $this->callWinitApi("isp.order.createOrder",$creatOrder);
        if(isset($result['code'])&&($result['code']==0)&&($result['msg']=='操作成功'))
        {   
            //$data = ['tracking_no' => $result['data']['orderNo'] ];
            //PackageModel::where('id',$package->id)->update($data);            
            return array('code' => 'success', 'result' => $result['data']['orderNo'] );        
        }else{        
            return array('code' => 'error', 'result' => 'error description.');
        }
    }
    
        
    public function getToken(){    
        $code = array();
        $code['action'] = "getToken";
        $code['data']['userName'] = $this->userName;
        $code['data']['passWord'] = $this->passWord;
        $url = 'http://erp.demo.winit.com.cn/ADInterface/api';
        return $this->curlPost($url,json_encode($code));        
    }
    
    public function getSign($token,$action,$data=''){
        $time =date('Y-m-d H:i:s');
        $string =$token.'action'.$action.'app_key'.$this->config['userId'].'data'.(string)json_encode($data,JSON_UNESCAPED_UNICODE).'formatjsonplatformsign_methodmd5timestamp'.$time.'version1.0'.$token;   
        $sign =strtoupper(md5($string));
        $this->sign = $sign;
    
    }
    
    public function setApi($warehouse){
        $token=array(
            999=>'B512F5AFBE10C0D709BF7E0AF2B0C3B6',
            1000 =>'069C80E8D3E89D0618A98CE62DDE824A',
        );
        $userName =array(
            999=>'qiongjierui@163.com',
            1000=>'wuliu@moonarstore.com'
        );
        $passWord =array(
            999=>'888',
            1000=>'salamoer123456',
        );
        $server_url=array(
            999=>'http://openapi.demo.winit.com.cn/openapi/service',
            1000=>'http://openapi.winit.com.cn/openapi/service',
        );
        
        $this->passWord = $passWord[$warehouse];
        $this->userName = $userName[$warehouse];       
        $this->server_url =$server_url[$warehouse];
        $this->token = $token[$warehouse];
    }
    
    public function callWinitApi($action,$data='{}'){
        $post_array = array();
        $post_array['action']=$action;
        $post_array['app_key'] = $this->userName;
        $post_array['data'] =$data;
        $post_array['format'] ='json';
        $post_array['language'] ="zh_CN";
        $post_array['platform'] ="";
        $this->getSign($this->token,$post_array['action'],$post_array['data']);
        $post_array['sign'] =$this->sign;
        $post_array['sign_method'] ="md5";
        $post_array['timestamp'] =date('Y-m-d H:i:s');
        $post_array['version'] ="1.0";    
        $headers = array("application/x-www-form-urlencoded; charset=gb2312");
        $result =  $this->curlPost($this->server_url,json_encode($post_array,JSON_UNESCAPED_UNICODE),$headers);
        return $result;
    }
    
}

?>