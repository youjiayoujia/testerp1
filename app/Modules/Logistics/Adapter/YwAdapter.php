<?php
namespace App\Modules\Logistics\Adapter;

/**
 * 
 * @author guoou
 * @abstract 2016/8/11
 */

class YwAdapter extends BasicAdapter
{
    public function __construct($config){
        $this->serverUrl = $config['url'];
        $this->userId = $config['userId'];
        $this->token = $config['key'];
    }
    
    public function getTracking($package){
        $response = $this->doUpload($package);
        if ($response['status'] != 0) {
            $result = [
                'code' => 'success',
                'result' =>$response['trackingNum'] //跟踪号
            ];            
        }else{
            $result =[
                'code' => 'error',
                'result' => $response['msg']
            ];
        }
        
        return $result;
    }
    
    public function doUpload($package){
        $epcode = '';
        $quantity = 0;
        $requestXmlBody  = '';
        $products_declared_value = 0;
        
        foreach ($package->items as $key => $item) {
            $quantity += $item->quantity;
            $products_declared_value += $item->item->product->declared_value;
            $products_declared_en = $item->item->product->declared_en;
            $products_declared_cn = $item->item->product->declared_cn;   
            $products_sku = $item->orderItem->sku;
            $warehouse_position_id = $item->warehouse_position_id;
        }
        $memo_str = $products_sku . ' * ' . $quantity . " (". $warehouse_position_id . ")\r\n";      
        $receiver_name = $package->shipping_firstname.' '.$package->shipping_lastname;
        $address = $package->shipping_address.' '.$package->shipping_address1;
        list($name,$channel) =  explode(',',$package->logistics->logistics_code);
        $userID = $this->userId;
        $weight = intval($package->weight);
        
        $requestXmlBody =   "<ExpressType>
                                <Epcode>$epcode</Epcode>
                                <Userid>$userID</Userid>
                                <Channel>$channel</Channel>                
                                <UserOrderNumber>$package->order_id</UserOrderNumber>
                                <SendDate>$package->shipped_at</SendDate>
                                <Receiver>
                                    <Userid>$userID</Userid>
                                    <Name>$receiver_name</Name>
                                    <Phone>$package->shipping_phone</Phone>
                                    <Mobile>NULL</Mobile>
                                    <Email>". $package->order->email ."</Email>
                                    <Company>NULL</Company>
                                    <Country>$package->shipping_country</Country>
                                    <Postcode>$package->shipping_zipcode</Postcode>
                                    <State>$package->shipping_state</State>
                                    <City>$package->shipping_city</City>
                                    <Address1>$address</Address1>
                                    <Address2>NULL</Address2>
                                </Receiver>
                                <Memo>$memo_str</Memo>
                                <Quantity>$quantity</Quantity>
                                <GoodsName>                                   
                                    <Userid>$userID</Userid>
                                    <NameCh>". $products_declared_cn ."</NameCh>
                                    <NameEn>" . substr($products_declared_en, 0, 190) . "</NameEn>                                                
                                    <Weight> $weight </Weight>
                                    <DeclaredValue>$products_declared_value</DeclaredValue>
                                    <DeclaredCurrency>". $package->order->currency ."</DeclaredCurrency>
                                    <MoreGoodsName>" . $products_declared_cn . "</MoreGoodsName>   
                                </GoodsName>
                            </ExpressType>";
        var_export($requestXmlBody);
        $url = $this->serverUrl . 'Users/'.$this->userId.'/Expresses';
        $result = $this->sendHttpRequest($url, 1, $requestXmlBody);
        $result_xml = simplexml_load_string($result);

        if ( $result_xml->Response->Success == 'true' ) {      
            $epcodeNode = $result_xml->CreatedExpress->Epcode;
            $YWcode = $result_xml->CreatedExpress->YanwenNumber;
            return array('status'=>1,'trackingNum'=>$YWcode);                             
        }else{
            $errorMsg = $result_xml->Response->ReasonMessage;
            return array('status'=>0,'msg'=>$errorMsg);
        }
    }
    
    public function sendHttpRequest($url, $post, $requestBody)
    {
        $headers = array(
            'Authorization: basic '. $this->token ,
            'Content-Type: text/xml; charset=utf-8',
        );
        $connection = curl_init();   
        
        curl_setopt($connection, CURLOPT_VERBOSE, 1);  
        curl_setopt($connection, CURLOPT_URL, $url);     
        curl_setopt($connection, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($connection, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($connection, CURLOPT_HTTPHEADER, $headers);
        if ($post) {
            curl_setopt($connection, CURLOPT_POST, 1);         
            curl_setopt($connection, CURLOPT_POSTFIELDS, $requestBody);
        }
    
        curl_setopt($connection, CURLOPT_RETURNTRANSFER, 1);    
        curl_setopt($connection, CURLOPT_TIMEOUT, 30);   
        $response = curl_exec($connection);    
        $curl_errno = curl_errno($connection);    
        $curl_error = curl_error($connection);         
        curl_close($connection);    
        if( $curl_errno > 0 ){
            return array('msg' => "请求错误: ($curl_errno): $curl_error\n", 'result' => false);
        }
    
        return $response;
    }
    
}

?>