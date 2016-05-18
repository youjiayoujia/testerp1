<?php
namespace App\Modules\Channel\Adapter;

/**
 * Created by PhpStorm.
 * User: Vincent
 * Date: 16/5/17
 * Time: 下午2:54
 */

use Tool;

Class AmazonAdapter implements AdapterInterface
{
    private $serviceUrl;
    private $signatureVersion = '2';
    private $signatureMethod = 'HmacSHA256';
    private $version = '2013-09-01';
    private $config = [];

    public function __construct($config)
    {
        $this->serviceUrl = $config['serviceUrl'];
        unset($config['serviceUrl']);
        $this->config = array_merge($config);
        $this->config['SignatureVersion'] = $this->signatureVersion;
        $this->config['SignatureMethod'] = $this->signatureMethod;
        $this->config['Version'] = $this->version;
    }

    public function getOrder($orderID)
    {
        $request['Action'] = 'GetOrder';
        $request['AmazonOrderId.Id.1'] = $orderID;
        return $this->setRequest('Orders', $request);
    }

    public function listOrders($startDate, $endDate, $status = [], $perPage = 10)
    {
        $request['Action'] = 'ListOrders';
        foreach ($status as $key => $value) {
            $i = $key + 1;
            $request['OrderStatus.Status.' . $i] = $value;
        }
        $request['MaxResultsPerPage'] = $perPage;
        $request['CreatedAfter'] = gmdate("Y-m-d\TH:i:s.\\0\\0\\0\\Z", strtotime($startDate));
        $request['CreatedBefore'] = gmdate("Y-m-d\TH:i:s.\\0\\0\\0\\Z", strtotime($endDate));
        return $this->setRequest('Orders', $request);
    }

    public function returnTrack()
    {
        // TODO: Implement returnTrack() method.
        echo "return Amazon Tracking Informations";
    }

    public function setRequest($type, $request)
    {
        $request['Timestamp'] = gmdate("Y-m-d\TH:i:s.\\0\\0\\0\\Z", time());
        $requestUrl = $this->setRequestUrl($type, $request);
        return simplexml_load_string(Tool::curl($requestUrl));
    }

    public function setRequestUrl($type, $request)
    {
        $baseUrl = $this->serviceUrl . '/' . $type . '/' . $this->version;
        $requests = array_merge($this->config, $request);
        $requestParams = [];
        foreach ($requests as $key => $value) {
            $key = str_replace("%7E", "~", rawurlencode($key));
            $value = str_replace("%7E", "~", rawurlencode($value));
            $requestParams[] = "{$key}={$value}";
        }
        sort($requestParams);
        $paramUrl = implode('&', $requestParams);
        $signature = $this->getSignature($baseUrl, $paramUrl);
        return $baseUrl . '?' . $paramUrl . '&Signature=' . $signature;
    }

    public function getSignature($baseUrl, $paramUrl)
    {
        $signatureArray = parse_url($baseUrl);
        $signatureString = 'GET' . "\n";
        $signatureString .= $signatureArray['host'] . "\n";
        $signatureString .= $signatureArray['path'] . "\n";
        $signatureString .= $paramUrl;
        $signature = hash_hmac("sha256", $signatureString, $this->config['AWS_SECRET_ACCESS_KEY'], true);
        $signature = urlencode(base64_encode($signature));
        return $signature;
    }
}