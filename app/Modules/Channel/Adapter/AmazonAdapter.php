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
    private $serviceUrl = 'https://mws.amazonservices.com';
    private $signatureVersion = '2';
    private $signatureMethod = 'HmacSHA256';
    private $version = '2013-09-01';
    private $request = [];

    public function __construct($config)
    {
        $this->request = array_merge($config);
        $this->request['SignatureVersion'] = $this->signatureVersion;
        $this->request['SignatureMethod'] = $this->signatureMethod;
        $this->request['Version'] = $this->version;
    }

    public function getOrder($orderID)
    {
        $this->request['Action'] = 'GetOrder';
        $this->request['AmazonOrderId.Id.1'] = $orderID;
        return $this->setRequest('Orders');
    }

    public function listOrders()
    {
        // TODO: Implement listOrders() method.
        echo "get Amazon Orders.";
    }

    public function returnTrack()
    {
        // TODO: Implement returnTrack() method.
        echo "return Amazon Tracking Informations";
    }

    public function setRequest($type)
    {
        $this->request['Timestamp'] = gmdate("Y-m-d\TH:i:s.\\0\\0\\0\\Z", time());
        $requestUrl = $this->setRequestUrl($type);
        return simplexml_load_string(Tool::curl($requestUrl));
    }

    public function setRequestUrl($type)
    {
        $baseUrl = $this->serviceUrl . '/' . $type . '/' . $this->version;
        $request = [];
        foreach ($this->request as $key => $value) {
            $key = str_replace("%7E", "~", rawurlencode($key));
            $value = str_replace("%7E", "~", rawurlencode($value));
            $request[] = "{$key}={$value}";
        }
        sort($request);
        $paramUrl = implode('&', $request);
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
        $signature = hash_hmac("sha256", $signatureString, $this->request['AWS_SECRET_ACCESS_KEY'], true);
        $signature = urlencode(base64_encode($signature));
        return $signature;
    }
}