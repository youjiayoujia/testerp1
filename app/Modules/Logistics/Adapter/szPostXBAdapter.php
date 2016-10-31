<?php
namespace App\Modules\Logistics\Adapter;

class szPostXBAdapter extends BasicAdapter
{
    public function __construct($config){
        $this->ShipServerUrl = $config['url'];
        $this->ecCompanyId =  $config['userId'];
        $this->scret = $config['userPassword'];
        $this->mailType = 'SALAMOER';
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
    }
    
    public function postCurlHttpsData($url, $data) { // 模拟提交数据函数
        $headers = array(
            'application/x-www-form-urlencoded; charset=UTF-8'
        );
    
        $curl = curl_init (); // 启动一个CURL会话
        curl_setopt ( $curl, CURLOPT_URL, $url ); // 要访问的地址
        curl_setopt ( $curl, CURLOPT_SSL_VERIFYPEER, 0 ); // 对认证证书来源的检查
        curl_setopt ( $curl, CURLOPT_SSL_VERIFYHOST, 1 ); // 从证书中检查SSL加密算法是否存在
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
    
  
    
}

?>