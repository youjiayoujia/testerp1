<?php
/**
 * Amazon接口Module 
 *
 * @author mc<178069409@qq.com>
 * Date: 2016-4-22 14:55
 *
 */
namespace App\Modules\Channels\Amazon;

use App\Modules\BaseChannelModule;

class AmazonModule extends BaseChannelModule
{
    //保存对应的地区亚马逊服务器地址
    private $serviceUrl;

    /**
     * 保存请求对应的参数 
     *
     * type array
     * private
     *
     */
    private $_config;

    /**
     * 构造函数 
     *
     * 初始化$_config 和 $serviceUrl
     *
     */
    function __construct()
    {
        //北美亚马逊地址
        $this->serviceUrl = "https://mws.amazonservices.com/Orders/2013-09-01?";

        $this->_config = config('setting.modules.amazon');  
    }

    /**
     * 获取订单 
     *
     * @param none 
     * @return view => data
     *
     */
    public function getOrder()
    {
        $this->_config['Action'] = 'GetOrder';
        $this->_config['AmazonOrderId.Id.1'] = '112-8698241-2648200';        
        $url = $this->getFinalUrl();
        $this->visitUrl($url);
    }

    /**
     * 订单列表 
     *
     * @param none
     * @return view => data
     *
     */
    public function listOrders()
    {
        $this->_config['MaxResultsPerPage'] = '20';
        $this->_config['Action'] = 'ListOrders';
        $this->_config['OrderStatus.Status.1'] = 'Unshipped';
        $this->_config['OrderStatus.Status.2'] = 'PartiallyShipped';
        $this->_config['CreatedAfter'] = gmdate("Y-m-d\TH:i:s.\\0\\0\\0\\Z", time()-24*60*60);
        $url = $this->getFinalUrl();
        $result = $this->visitUrl($url);
        while($result->ListOrdersResult->NextToken) {
            $result = $this->listOrdersByNextToken($result->ListOrdersResult->NextToken);
        }
    }

    /**
     * 获取后续订单列表 
     *
     * @param $nextToken
     * @return result
     *
     */
    public function listOrdersByNextToken($nextToken)
    {
        $this->_config['NextToken'] = $nextToken;
        $url = $this->getFinalUrl();
        $result = $this->visitUrl($url);

        return $result;
    }

    /**
     * 订单详情 
     *
     * @param none
     * @return view => data
     *
     */
    public function listOrderItems()
    {
        $this->_config['Action'] = 'ListOrderItems';
        $this->_config['AmazonOrderId'] = '112-8698241-2648200';
        $url = $this->getFinalUrl();
        $result = $this->visitUrl($url);
        while($result->ListOrderItemsResult->NextToken) {
            $result = $this->listOrderItemsByNextToken($result->ListOrderItemsResult->NextToken);
        }
    }

    /**
     * 获取后续订单详情 
     *
     * @param $nextToken
     * @return result
     *
     */
    public function listOrderItemsByNextToken($nextToken)
    {
        $this->_config['NextToken'] = $nextToken;
        $url = $this->getFinalUrl();
        $result = $this->visitUrl($url);

        return $result;
    }
    /**
     * 获取最终的url 
     *
     * @param none
     * @return string url
     *
     */
    public function getFinalUrl()
    {
        $url = $this->serviceUrl.$this->signArrToString();
        $url .= '&Signature='.$this->createSignatureV2();
        return $url;
    }


    /**
     * 版本2生成的签名 
     *
     * @param none
     * @return signature
     *
     */
    public function createSignatureV2()
    {
        $url = $this->urlParse();
        $sign  = 'GET' . "\n";
        $sign .= $url['host'] . "\n";
        $sign .= $url['path'] . "\n";
        $sign .= $this->signArrToString();
        $signature = hash_hmac("sha256", $sign, config('setting.AWS_SECRET_ACCESS_KEY'), true);
        $signature = urlencode(base64_encode($signature));

        return $signature;
    }

    /**
     * 将url参数数组转成url格式 
     *
     * @param none
     * @return string
     *
     */
    private function signArrToString()
    {
        $config = $this->_config;
        $url = array();
        foreach ($config as $key => $val) {
            $key = str_replace("%7E", "~", rawurlencode($key));
            $val = str_replace("%7E", "~", rawurlencode($val));
            $url[] = "{$key}={$val}";
        }
        sort($url);
        $string = implode('&', $url);
        return $string;
    }

    /**
     * 对url进行解析 
     *
     * @return array  | scheme,host,path
     *
     */
    private function urlParse()
    {
        return parse_url($this->serviceUrl);
    }

    /**
     * 访问url  
     *
     * @param url 地址
     * @return view => data
     *
     */
    private function visitUrl($url)
    {
        $ch = curl_init($url);
        curl_setopt($ch,CURLOPT_HEADER,0);
        curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);  
        $res = curl_exec($ch);
        curl_close($ch);

        $result_string = simplexml_load_string($res);
        echo "<pre>";
        print_r($result_string);
        echo "</pre>";
        return $result_string;
    }


}