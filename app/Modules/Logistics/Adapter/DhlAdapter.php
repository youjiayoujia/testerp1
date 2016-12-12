<?php
/** 线上Eub
 * Created by PhpStorm.
 * User: lidabiao
 * Date: 2016-12-12
 * Time: 13:25
 */

namespace App\Modules\Logistics\Adapter;

use App\Models\Channel\AccountModel;
class DhlAdapter extends BasicAdapter
{
    public function __construct($config)
    {
        //$this->_authenticate = $config['key'];
        //$this->_customer_code = $config['userId'];
        //$this->_vip_code = $config['userPassword'];
//        $this->GetShipHost='https://api.dhlecommerce.asia/rest/v2/Label';//获取追踪号地址
//        $this->CheckOutHost='https://api.dhlecommerce.asia/rest/v2/Order/Shipment/CloseOut';//确认发货地址
//        $this->getTokenUrl = "https://api.dhlecommerce.asia/rest/v1/OAuth/AccessToken?returnFormat=json";//获取TOKEN地址
//        $this->account = '5243380896';//账号
        $this->qz = 'CNAMM';//物流号前缀
        ////////////////////////////////////////测试环境数据/////////////////////////////////////
		$this->GetShipHost='https://apitest.dhlecommerce.asia/rest/v2/Label';//获取追踪号地址
		$this->CheckOutHost='https://apitest.dhlecommerce.asia/rest/v2/Order/Shipment/CloseOut';//确认发货地址
		$this->getTokenUrl = "https://apitest.dhlecommerce.asia/rest/v1/OAuth/AccessToken?returnFormat=json";//获取TOKEN地址
        $this->account = '520285';//账号
        $this->checkToken($this->getTokenUrl,$this->account);
    }
    public function checkToken($url,$account){
        $url = $url.'&clientId=LTExMTgwNTI4MTY=&password=APITest1';
        $result = $this->getCurlHttpsData($url);
        $result = $this->getCurlHttpsData($url);
        $result = json_decode($result);
        $status = $result->accessTokenResponse->responseStatus->code;
        $token = $result->accessTokenResponse->token;
        if($status == '100000' && $token){
            //获取token成功
            $this->token = $token;
        }
    }
    public function getTracking($orderInfo){
        echo 32;exit;
    }
    public function getCurlHttpsData($url) { // 模拟提交数据函数
        $headers = array(
            'Content-Type: application/json'
        );
        $curl = curl_init (); // 启动一个CURL会话
        curl_setopt ( $curl, CURLOPT_URL, $url ); // 要访问的地址
        curl_setopt ( $curl, CURLOPT_SSL_VERIFYPEER, 0 ); // 对认证证书来源的检查
        curl_setopt ( $curl, CURLOPT_SSL_VERIFYHOST, 1 ); // 从证书中检查SSL加密算法是否存在
        //curl_setopt ( $curl, CURLOPT_USERAGENT, $_SERVER ['HTTP_USER_AGENT'] ); // 模拟用户使用的浏览器
        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
        curl_setopt ( $curl, CURLOPT_FOLLOWLOCATION, 1 ); // 使用自动跳转
        curl_setopt ( $curl, CURLOPT_AUTOREFERER, 1 ); // 自动设置Referer
        curl_setopt ( $curl, CURLOPT_POST, 0 ); // 发送一个常规的Post请求
        //curl_setopt ( $curl, CURLOPT_POSTFIELDS, $data ); // Post提交的数据包
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