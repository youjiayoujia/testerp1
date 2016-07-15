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
/*************************************************************************************/
/**
 * $model = new AmazonModule();
 *       $model->returnTracking();
 */
    public function returnTracking()
    {
        $this->_config['Action'] = 'SubmitFeed';
        $this->_config['FeedType'] = '_POST_ORDER_FULFILLMENT_DATA_';
        $this->_config['Version'] = '2009-01-01';
        unset($this->_config['MarketplaceId.Id.1']);
        $this->_config['MarketplaceIdList.Id.1'] = 'ATVPDKIKX0DER';
        $tmp_url = "mws.amazonservices.ca";
        $sign  = 'POST' . "\n";
        $sign .= $tmp_url . "\n";
        $sign .= "/" . "\n";
        $sign .= $this->signArrToString();
        $signature = hash_hmac("sha256", $sign, config('setting.AWS_SECRET_ACCESS_KEY'), true);
        $signature = urlencode(base64_encode($signature));
        $this->_config['Signature'] = $signature;
        $url = [];
        foreach($this->_config as $key => $value) {
            $url[] = "{$key}={$value}";
        }
        sort($url);
        $string = implode('&', $url);
        $test = $this->getXML([['2', '3', '4', '5', '6', ['item1'=>'12']]],'32');
        $tmp_header = ["Content-Type: text/xml", "User-Agent:php-amazon-mws/0.0.1 (Language=php)", "Host:mws.amazonservices.ca", "Content-MD5:".base64_encode(md5($test))];
        $ch = curl_init($tmp_url);
        curl_setopt($ch,CURLOPT_HEADER,1);
        curl_setopt($ch,CURLOPT_RETURNTRANSFER,0);
        curl_setopt($ch,CURLOPT_POST,1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);  
        curl_setopt($ch,CURLOPT_POSTFIELDS,$string);
        curl_setopt($ch,CURLOPT_HTTPHEADER, $tmp_header);
        $res = curl_exec($ch);
        var_dump(curl_error($ch));
        curl_close($ch);
        var_dump($res);
    }

//A3THBIK7QYKUUV account id  marchant_id就是account_id
    public function getXML($arr, $marchant)
    {
        $str = "<?xml version='1.0' encoding='UTF-8'?>
    <AmazonEnvelope xsi:noNamespaceSchemaLocation='amzn-envelope.xsd' xmlns:xsi='http://www.w3.org/2001/XMLSchema-instance'>
        <Header>
            <DocumentVersion>1.01</DocumentVersion>
            <MerchantIdentifier>".$marchant."</MerchantIdentifier>
        </Header>
        <MessageType>OrderFulfillment</MessageType>";
        foreach($arr as $key => $value)
        {
            $str .= $this->getSingleXML(($key+1),$value['0'],$value['1'],$value['2'],$value['3'],$value['4'],$value['5']);
        }
    
        $str .= "</AmazonEnvelope>";
        return $str;
    }

    public function getSingleXML($i, $amazonOrderId, $FulfillmentDate, $CarrierName, $ShippingMethod, $shipperTrackingNumber, $arr)
    {
        $str = "<Message>
            <MessageID>".$i."</MessageID>
            <OrderFulfillment>
                <AmazonOrderID>".$amazonOrderId."</AmazonOrderID>
                <FulfillmentDate>".$FulfillmentDate."</FulfillmentDate>
                <FulfillmentData>
                    <CarrierName>".$CarrierName."</CarrierName>
                    <ShippingMethod>".$ShippingMethod."</ShippingMethod>
                    <ShipperTrackingNumber>".$shipperTrackingNumber."</ShipperTrackingNumber>
                </FulfillmentData>";
        foreach($arr as $key => $value) {
            $str .= "<Item>
                    <AmazonOrderItemCode>".$key."</AmazonOrderItemCode>
                    <Quantity>".$value."</Quantity>
                </Item>";
        }
        $str .= "</OrderFulfillment>
            </Message>";
        return $str;
    }
    /******************************************************************************************/
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