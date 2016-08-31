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

    
    public function getProductInfo()
    {
        $this->serviceUrl = "https://mws.amazonservices.com/Products/2011-10-01?";
        $this->_config['Action'] = 'GetMatchingProductForId';
        $this->_config['IdType'] = 'SellerSKU';  
        $this->_config['Version'] = '2011-10-01';
        $this->_config['IdList.Id.1'] = '606*TS0074W2[SUNUS]';
        unset($this->_config['MarketplaceId.Id.1']);
        $this->_config['MarketplaceId'] = 'ATVPDKIKX0DER';
        //var_dump($this->_config);exit;
        $url = $this->getFinalUrl();
        //var_dump($url);exit;
        $this->visitUrl($url);
    }

    /**
     * 库存，有两个totalSupplyQuantity,InStockSupplyQuantity 
     *
     */
    public function listSupplier()
    {
        $this->serviceUrl = "https://mws.amazonaws.com/";
        $this->_config['Action'] = 'ListInventorySupply';
        $this->_config['Version'] = '2009-01-01';
        $this->_config['SellerSkus.member.1'] = '606*TS0074W2[SUNUS]';
        $this->_config['SellerSkus.member.2'] = '606*TS0074W1[SUNUS]';
        $this->_config['ResponseGroup'] = 'Detail';
        unset($this->_config['MarketplaceId.Id.1']);
        $this->_config['MarketplaceId'] = 'ATVPDKIKX0DER';
        //var_dump($this->_config);exit;
        $url = $this->getFinalUrl();
        $this->visitUrl($url);
    }

    public function requestReport()
    {
        $this->serviceUrl = "https://mws.amazonaws.com";
        $this->_config['Action'] = 'RequestReport';
        $this->_config['Version'] = '2009-01-01';
        $this->_config['ReportType'] = '_GET_AMAZON_FULFILLED_SHIPMENTS_DATA_';
        //var_dump($this->_config);exit;
        $tmp_url = "https://mws.amazonservices.com";
        $tmp_arr = parse_url($tmp_url);
        $sign  = 'GET' . "\n";
        $sign .= "mws.amazonservices.com" . "\n";
        $sign .= "/" . "\n";
        $tmp_sigtoString = $this->signArrToString();
        $sign .= $tmp_sigtoString;
        $signature = hash_hmac("sha256", $sign, config('setting.AWS_SECRET_ACCESS_KEY'), true);
        $signature = urlencode(base64_encode($signature));
        $string = $tmp_sigtoString.'&Signature='.$signature;
        $string1 = $tmp_url."/?".$string;

        $ch = curl_init($string1);
        curl_setopt($ch,CURLOPT_HEADER,0);
        curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);  
        $res = curl_exec($ch);
        curl_close($ch);
        $result_string = simplexml_load_string($res);
        $reportRequestId = $result_string->RequestReportResult->ReportRequestInfo->ReportRequestId;
        echo "<pre>";
        print_r($result_string);
        echo "</pre>";
        return (string)$reportRequestId;
    }

    public function getReportRequestList($id)
    {
        $this->serviceUrl = "https://mws.amazonaws.com";
        $this->_config['Action'] = 'GetReportRequestList';
        $this->_config['ReportRequestIdList.Id.1'] = $id;
        $this->_config['Version'] = '2009-01-01';
        var_dump($this->_config);
        $tmp_url = "https://mws.amazonservices.com";
        $tmp_arr = parse_url($tmp_url);
        $sign  = 'GET' . "\n";
        $sign .= "mws.amazonservices.com" . "\n";
        $sign .= "/" . "\n";
        $tmp_sigtoString = $this->signArrToString();
        $sign .= $tmp_sigtoString;
        $signature = hash_hmac("sha256", $sign, config('setting.AWS_SECRET_ACCESS_KEY'), true);
        $signature = urlencode(base64_encode($signature));
        $string = $tmp_sigtoString.'&Signature='.$signature;
        $string1 = $tmp_url."/?".$string;

        $ch = curl_init($string1);
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

    public function getReport($id) 
    {
        $this->serviceUrl = "https://mws.amazonaws.com";
        $this->_config['Action'] = 'GetReport';
        $this->_config['ReportId'] = $id;
        $this->_config['Version'] = '2009-01-01';
        $tmp_url = "https://mws.amazonservices.com";
        $tmp_arr = parse_url($tmp_url);
        $sign  = 'GET' . "\n";
        $sign .= "mws.amazonservices.com" . "\n";
        $sign .= "/" . "\n";
        $tmp_sigtoString = $this->signArrToString();
        $sign .= $tmp_sigtoString;
        $signature = hash_hmac("sha256", $sign, config('setting.AWS_SECRET_ACCESS_KEY'), true);
        $signature = urlencode(base64_encode($signature));
        $string = $tmp_sigtoString.'&Signature='.$signature;
        $string1 = $tmp_url."/?".$string;

        $ch = curl_init($string1);
        curl_setopt($ch,CURLOPT_HEADER,0);
        curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);  
        $res = curl_exec($ch);
        curl_close($ch);
        echo "<pre>";
        print_r($res);
        echo "</pre>";
        return $res;
    }
/*************************************************************************************/
/**
 * $model = new AmazonModule();
 *       $model->returnTracking();
 */
    public function returnTracking()
    {
        $tmp_ddd = $this->testXML();
        // $fd = fopen('d:/test.xml', 'w+');
        // fwrite($fd, $tmp_ddd);
        // rewind($fd);
        $this->_config['Action'] = 'SubmitFeed';
        $this->_config['FeedType'] = '_POST_ORDER_FULFILLMENT_DATA_';
        $this->_config['Version'] = '2009-01-01';
        $this->_config['MarketplaceIdList.Id.1'] = 'ATVPDKIKX0DER';
        $this->_config['PurgeAndReplace'] = 'false';
        $this->_config['Merchant'] = 'A3THBIK7QYKUUV';
        unset($this->_config['SellerId']);
        unset($this->_config['MarketplaceId.Id.1']);
        //rewind($fd);
        $tmp_url = "https://mws.amazonservices.com";
        $tmp_arr = parse_url($tmp_url);
        $sign  = 'POST' . "\n";
        $sign .= $tmp_arr['host'] . "\n";
        $sign .= "/" . "\n";
        $tmp_sigtoString = $this->signArrToString();
        $sign .= $tmp_sigtoString;
        $signature = hash_hmac("sha256", $sign, config('setting.AWS_SECRET_ACCESS_KEY'), true);
        $signature = urlencode(base64_encode($signature));
        $string = $tmp_sigtoString.'&Signature='.$signature;
        $tmp_header = ["Content-Type: text/xml", "Host: mws.amazonservices.com", "Content-MD5:".base64_encode(md5($this->testXML(), true))];
        $string1 = $tmp_url."/?".$string;


        $ch = curl_init($string1);
        curl_setopt($ch,CURLOPT_HEADER,true);
        curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
        curl_setopt($ch,CURLOPT_POST,1);
        curl_setopt($ch,CURLOPT_SSL_VERIFYPEER, false); 
        curl_setopt($ch,CURLOPT_POSTFIELDS,$tmp_ddd);
        curl_setopt($ch,CURLOPT_HTTPHEADER, $tmp_header);
        $res = curl_exec($ch);
        curl_close($ch);
        print_r($res);
        echo "========================================================";
        var_dump($res);
    }

    public function testXML()
    {
        return "<?xml version='1.0' encoding='UTF-8'?><AmazonEnvelope xsi:noNamespaceSchemaLocation='amzn-envelope.xsd'xmlns:xsi='http://www.w3.org/2001/XMLSchema-instance'><Header><DocumentVersion>1.01</DocumentVersion><MerchantIdentifier>A3THBIK7QYKUUV</MerchantIdentifier></Header><MessageType>OrderFulfillment</MessageType><Message><MessageID>1</MessageID><OrderFulfillment><AmazonOrderID>116-6770178-1261059</AmazonOrderID><FulfillmentDate>2016-03-10T07:59:59+00:00</FulfillmentDate><FulfillmentData><CarrierName>China Post</CarrierName><ShippingMethod>e-packet</ShippingMethod><ShipperTrackingNumber>LS598110942CN</ShipperTrackingNumber></FulfillmentData><Item><AmazonOrderItemCode>06285235305074</AmazonOrderItemCode><Quantity>1</Quantity></Item></OrderFulfillment></Message></AmazonEnvelope>";
    }

//A3THBIK7QYKUUV account id  marchant_id就是account_id
    public function getXML($arr)
    {
        $str = "<?xml version='1.0' encoding='UTF-8'?>
    <AmazonEnvelope xsi:noNamespaceSchemaLocation='amzn-envelope.xsd' xmlns:xsi='http://www.w3.org/2001/XMLSchema-instance'>
        <Header>
            <DocumentVersion>1.01</DocumentVersion>
            <MerchantIdentifier>A3THBIK7QYKUUV</MerchantIdentifier>
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