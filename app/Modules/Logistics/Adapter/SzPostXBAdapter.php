<?php
namespace App\Modules\Logistics\Adapter;

class SzPostXBAdapter extends BasicAdapter
{
    public function __construct($config){
        $this->ShipServerUrl = $config['url'];
        $this->ecCompanyId =  $config['userId'];
        $this->scret = $config['userPassword'];
        $this->mailType = 'SALAMOER';
        $this->ServerUrl = 'http://shipping.11185.cn:8000/mqrysrv/OrderImportMultiServlet';
    }
    
    public function getTracking($package){
        $orderStr = '';
        $dateTime = date('Y-m-d H:i:s');
         list($name, $channel) = explode(',',$package->logistics->type);   
        $orderStr .= '{"ecCompanyId":"'.$this->ecCompanyId.'","eventTime":"'.$dateTime.'","logisticsOrderId":"'.$package->order->channel_ordernum.'","LogisticsCompany":"POST","LogisticsBiz":"'.$channel.'","mailType":"'.$this->mailType.'","faceType":"1"},';
        
        $orderStr = trim($orderStr,',');
        $orderStr = '{"order": ['.$orderStr.']}';
        $orderStr = json_decode($orderStr);
        $orderStr = json_encode($orderStr);
        $newdata =  base64_encode(pack('H*', md5($orderStr.$this->scret)));
        $url = $this->ShipServerUrl;
        $postD = array();
        $postD['logisticsOrder'] =$orderStr;
        $postD['data_digest'] =$newdata;
        $postD['msg_type'] ='B2C_TRADE';
        $postD['ecCompanyId'] =$this->ecCompanyId;
        $postD['version'] ='1.0';
        
        $url1 = '';
        foreach($postD as $key=>$v){
            $url1.=$key.'='.$v.'&';
        }
        $url1 = trim($url1,'&');
        $postD = http_build_query($postD);
        
        $result = $this->postCurlHttpsData($url,$url1);
        $result = json_decode($result,true);
        if($result['return_success'] == 'true'){
            $barCodeList = $result['barCodeList'];
            foreach($barCodeList as $v){
                $shipcode = $v['bar_code'];
                $orderId = $v['logisticsOrderId'];
                $res = $this->sendOrder($package, $shipcode);
                if($res === true){
                    return array('code' => 'success', 'result' => $shipcode);
                }else{
                    return array('code' => 'error','result' => 'error description.');
                }               
            }
        }else{
            return array('code' => 'error','result' => 'error description.');
        }
            
        
    }
    
    public function sendOrder($package,$shipcode){
        $proStr = '';
        $productNum = 0;
        $products_declared_cn = $package->items ? $package->items->first()->item->product->declared_cn : '裙子';
        $products_declared_en = $package->items ? $package->items->first()->item->product->declared_en : 'skirt';
        $totalWeight          = $package->total_weight * 1000;
        $totalValue           = $package->total_price * 1000;
        $category_name        = $package->items ? ($package->items->first()->item->catalog ? $package->items->first()->item->catalog->name : '裙子') : '裙子';      //获取分类信息
        $category_name_en     = $package->items ? ($package->items->first()->item->catalog ? $package->items->first()->item->catalog->c_name : 'skirt') : 'skirt';
        $productId            = $package->items ? $package->items->first()->item->product_id : '';
        foreach ($package->items as $packageItem) {
            $productNum += $packageItem->quantity;
        }
        $proStr .='<product><productNameCN>'.$products_declared_cn.'</productNameCN>
        <productNameEN>'.$products_declared_en.'</productNameEN>
        <productQantity>'.$productNum.'</productQantity>
        <productCateCN>'.$category_name.'</productCateCN>
        <productCateEN>'.$category_name_en.'</productCateEN>
        <productId>'.$productId.'</productId>
        <producingArea>CN</producingArea>
        <productWeight>'.$totalWeight.'</productWeight>
        <productPrice>'.$totalValue.'</productPrice></product>';
      
        if($package->warehouse_id == 3){
            //深圳仓
            $this->sendInfo = array(
                'j_company' => 'SALAMOER',                          //寄件人公司
                'j_contact' => 'huangchaoyun',                      //寄件人
                'j_tel' => '18038094536',                           //电话
                'j_address1' => '2nd Floor,Buliding 6,No. 146 Pine Road,Mengli Garden Industrial, Longhua District,',//地址
                /* 'j_address2' => 'No.41',
                'j_address3' =>' Wuhe Road South LONGGANG', */
                'j_province' => 'GUANGDONG',                        //省
                'j_city' => 'SHENZHEN',                             //市
                'j_post_code' => '518129',                          //邮编
                'j_country' => 'CN',                                //国家
                'custid' => '7555769565'
            );
        }elseif($package->warehouse_id == 4){
            //义乌仓
            $this->sendInfo=array(
                'j_company' => 'JINHUA MOONAR',                     //寄件人公司
                'j_contact' => 'xiehongjun',                        //寄件人
                'j_tel' => '15024520515',                           //电话
                'j_address1' => 'Buliding 1-4, Jinyi Postal Park, No.2011',//地址
                'j_address2' => 'No.2011',//地址
                'j_address3' =>'JinGangDaDao West, JINDONG',
                'j_province' => 'ZHEJIANG',                         //省
                'j_city' => 'JINHUA',                               //市
                'j_post_code' => '321000',                          //邮编
                'j_country' => 'CN',                                //国家
                'custid' => '5796625949'
            );
        }
         
        $dateTime = date('Y-m-d H:i:s');
        $batchNo = date('Ymd');
        $orderId = $package->order->channel_ordernum;       //订单ID
        list($name, $channel) = explode(',',$package->logistics->type);
        $str = '';
        $str .="<logisticsEventsRequest><logisticsEvent>
<eventHeader>
<eventType>LOGISTICS_BATCH_SEND</eventType>
<eventTime>".$dateTime."</eventTime>
<eventSource>taobao</eventSource>
<eventTarget>NPP</eventTarget>
</eventHeader>
<eventBody>
<order>
<orderInfos>
".$proStr."
</orderInfos>
<ecCompanyId>".$this->ecCompanyId."</ecCompanyId>
<logisticsOrderId>".$orderId."</logisticsOrderId>
<isItemDiscard>true</isItemDiscard>
<mailNo>".$shipcode."</mailNo>
<LogisticsCompany>POST</LogisticsCompany>
<LogisticsBiz>".$channel."</LogisticsBiz>
<ReceiveAgentCode>POST</ReceiveAgentCode>
<Rcountry>".$package->shipping_country."</Rcountry>
<Rcity>".$package->shipping_city."</Rcity>
<Raddress>".$package->shipping_address.' '.$package->shipping_address1."</Raddress>
<Rpostcode>".$package->shipping_zipcode."</Rpostcode>
<Rname>".$package->shipping_firstname . ' ' . $package->shipping_lastname."</Rname>
<Rphone>".$package->shipping_phone."</Rphone>
<Sname>".$this->sendInfo['j_contact']."</Sname>
<Sprovince>".$this->sendInfo['j_province']."</Sprovince>
<Scity>".$this->sendInfo['j_city']."</Scity>
<Saddress>".$this->sendInfo['j_address1']."</Saddress>
<Sphone>".$this->sendInfo['j_tel']."</Sphone>
<Spostcode>".$this->sendInfo['j_post_code']."</Spostcode>
<Itotleweight>". $totalWeight."</Itotleweight>
<Itotlevalue>". $totalValue."</Itotlevalue>
<totleweight>". $totalWeight."</totleweight>
<hasBattery>false</hasBattery>
<country>CN</country>
<mailKind>3</mailKind>
<mailClass>l</mailClass>
<batchNo>".$batchNo."</batchNo>
<mailType>".$this->mailType."</mailType>
<faceType>2</faceType>
<undeliveryOption>2</undeliveryOption>
</order>
</eventBody>
</logisticsEvent>
</logisticsEventsRequest>";
        $data=preg_replace('/&/',' ',$str);
        $newdata =  base64_encode(pack('H*', md5($data.$this->scret)));

        $url = $this->ServerUrl;        
        $postD = array();
        $postD['logistics_interface'] =$data;
        $postD['data_digest'] =$newdata;
        $postD['msg_type'] ='B2C_TRADE';
        $postD['ecCompanyId'] =$this->ecCompanyId;
        $postD['version'] ='2.0';
        $result = $this->postCurlHttpsData($url,$postD);
        $result = $this->XmlToArray($result);
        echo "<pre/>";var_dump($postD);
        var_dump($result);
        if($result['responseItems']['response']['success'] == 'true'){
            return true;        
        }else{
           return false;
        }
    }    
   
    
    public function postCurlHttpsData($url, $data) { // 模拟提交数据函数
        $headers = array(     
            'application/x-www-form-urlencoded; charset=UTF-8'
        );
    
        $curl = curl_init (); // 启动一个CURL会话
        curl_setopt ( $curl, CURLOPT_URL, $url ); // 要访问的地址
        //curl_setopt ( $curl, CURLOPT_SSL_VERIFYPEER, 0 ); // 对认证证书来源的检查
        //curl_setopt ( $curl, CURLOPT_SSL_VERIFYHOST, 2 ); // 从证书中检查SSL加密算法是否存在
        //curl_setopt ( $curl, CURLOPT_USERAGENT, $_SERVER ['HTTP_USER_AGENT'] ); // 模拟用户使用的浏览器
        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
        curl_setopt ( $curl, CURLOPT_FOLLOWLOCATION, 1 ); // 使用自动跳转
        curl_setopt ( $curl, CURLOPT_AUTOREFERER, 1 ); // 自动设置Referer
        curl_setopt ( $curl, CURLOPT_POST, 1 ); // 发送一个常规的Post请求
        curl_setopt ( $curl, CURLOPT_POSTFIELDS, $data); // Post提交的数据包
        curl_setopt ( $curl, CURLOPT_TIMEOUT, 30 ); // 设置超时限制防止死循环
        curl_setopt ( $curl, CURLOPT_HEADER, 0 ); // 显示返回的Header区域内容
        curl_setopt ( $curl, CURLOPT_RETURNTRANSFER, 1 ); // 获取的信息以文件流的形式返回
        $tmpInfo = curl_exec ( $curl ); // 执行操作
        if (curl_errno ( $curl )) {
            die(curl_error ( $curl )); //异常错误
        }
        curl_close ( $curl ); // 关闭CURL会话
        return $tmpInfo; // 返回数据
    }
    
    public function XmlToArray($xml)
    {
        $array = (array)(simplexml_load_string($xml));
        foreach ($array as $key => $item) {
    
            $array[$key] = $this->struct_to_array((array)$item);
        }
        return $array;
    }
    public function struct_to_array($item)
    {
        if (!is_string($item)) {
    
            $item = (array)$item;
            foreach ($item as $key => $val) {
    
                $item[$key] = $this->struct_to_array($val);
            }
        }
        return $item;
    }
    
  
    
}

?>