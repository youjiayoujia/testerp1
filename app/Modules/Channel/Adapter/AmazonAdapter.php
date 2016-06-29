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

    /**
     * 获取单个订单内容
     * @param $orderID
     * @return array|bool
     */
    public function getOrder($orderID)
    {
        $request['Action'] = 'GetOrder';
        $request['AmazonOrderId.Id.1'] = $orderID;
        $response = $this->setRequest('Orders', $request);
        $order = $response->GetOrderResult->Orders->Order;
        if ($order) {
            $orderItems = $this->getOrderItems($order->AmazonOrderId);
            return $this->parseOrder($order, $orderItems);
        }
        return false;
    }

    /**
     * 获取订单列表
     * @param $startDate
     * @param $endDate
     * @param array $status
     * @param int $perPage
     * @return array
     */
    public function listOrders($startDate, $endDate, $status = [], $perPage = 10)
    {
        $orders = [];
        $nextToken = null;
        do {
            $request = [];
            if ($nextToken) {
                $request['Action'] = 'ListOrdersByNextToken';
                $request['NextToken'] = $nextToken;
            } else {
                $request['Action'] = 'ListOrders';
                foreach ($status as $key => $value) {
                    $request['OrderStatus.Status.' . ($key + 1)] = $value;
                }
                $request['MaxResultsPerPage'] = $perPage;
                $request['LastUpdatedAfter'] = gmdate("Y-m-d\TH:i:s.\\0\\0\\0\\Z", strtotime($startDate));
                if ($endDate) {
                    $request['LastUpdatedBefore'] = gmdate("Y-m-d\TH:i:s.\\0\\0\\0\\Z", strtotime($endDate));
                }
            }
            $response = $this->setRequest('Orders', $request);
            if (isset($response->Error)) {
                continue;
            }
            $responseOrders = $nextToken ? $response->ListOrdersByNextTokenResult : $response->ListOrdersResult;
            foreach ($responseOrders->Orders->Order as $order) {
                $orderItems = $this->getOrderItems($order->AmazonOrderId);
                $orders[] = $this->parseOrder($order, $orderItems);
            }
            $nextToken = $responseOrders->NextToken;
        } while ($nextToken);
        return $orders;
    }

    /**
     * 获取订单行数据
     * @param $orderId
     * @return array
     */
    public function getOrderItems($orderId)
    {
        $items = [];
        $nextToken = null;
        do {
            if ($nextToken) {
                $request['Action'] = 'ListOrderItemsByNextToken';
                $request['NextToken'] = $nextToken;
            } else {
                $request['Action'] = 'ListOrderItems';
                $request['AmazonOrderId'] = $orderId;
            }
            $response = $this->setRequest('Orders', $request);
            if (isset($response->Error)) {
                break;
            }
            $responseOrderItems = $nextToken ? $response->ListOrderItemsByNextTokenResult : $response->ListOrderItemsResult;
            foreach ($responseOrderItems->OrderItems->OrderItem as $orderItem) {
                $items[] = $this->parseOrderItem($orderItem);
            }
            $nextToken = $responseOrderItems->NextToken;
        } while ($nextToken);
        return $items;
    }

    /**
     * 解析返回订单
     * @param $order
     * @param $orderItems
     * @return array
     */
    public function parseOrder($order, $orderItems)
    {
        $shippingName = explode(' ', $order->ShippingAddress->Name);
        $result = [
            'channel_ordernum' => (string)$order->AmazonOrderId,
            'email' => (string)$order->BuyerEmail,
            'amount' => (float)$order->OrderTotal->Amount,
            'currency' => (string)$order->OrderTotal->CurrencyCode,
            'status' => 'PAID',
            'payment' => (string)$order->PaymentMethod,
            'shipping' => (string)$order->ShipmentServiceLevelCategory,
            'shipping_firstname' => isset($shippingName[0]) ? $shippingName[0] : '',
            'shipping_lastname' => isset($shippingName[1]) ? $shippingName[1] : '',
            'shipping_address' => (string)$order->ShippingAddress->AddressLine1,
            'shipping_address1' => (string)$order->ShippingAddress->AddressLine2,
            'shipping_city' => (string)$order->ShippingAddress->City,
            'shipping_state' => (string)$order->ShippingAddress->StateOrRegion,
            'shipping_country' => (string)$order->ShippingAddress->CountryCode,
            'shipping_zipcode' => (string)$order->ShippingAddress->PostalCode,
            'shipping_phone' => (string)$order->ShippingAddress->Phone,
            'payment_date' => (string)$order->PurchaseDate,
            'create_time' => (string)$order->PurchaseDate,
            'fulfill_by' => (string)$order->FulfillmentChannel,
            'items' => $orderItems
        ];
        return $result;
    }

    /**
     * 解析返回订单行
     * @param $orderItem
     * @return array
     */
    public function parseOrderItem($orderItem)
    {
        preg_match('/001\*FBA(.+?)\[/i', (string)$orderItem->SellerSKU, $result);
        if ($result) {
            $sku = $result[1];
        } else {
            preg_match('/001\*(.+?)\[/i', (string)$orderItem->SellerSKU, $result);
            $sku = $result ? $result[1] : '';
        }
        $result = [
            'sku' => $sku,
            'channel_sku' => (string)$orderItem->SellerSKU,
            'quantity' => (int)$orderItem->QuantityOrdered,
            'price' => (float)$orderItem->ItemPrice->Amount,
            'currency' => (string)$orderItem->ItemPrice->CurrencyCode,
        ];
        return $result;
    }

    public function returnTrack()
    {
        // TODO: Implement returnTrack() method.
        echo "return Amazon Tracking Informations";
    }

    /**
     * 发送请求
     * @param $type
     * @param $request
     * @return \SimpleXMLElement
     */
    public function setRequest($type, $request)
    {
        $request['Timestamp'] = gmdate("Y-m-d\TH:i:s.\\0\\0\\0\\Z", time());
        $requestUrl = $this->setRequestUrl($type, $request);
        return simplexml_load_string(Tool::curl($requestUrl));
    }

    /**
     * 获取请求URL
     * @param $type
     * @param $request
     * @return string
     */
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

    /**
     * 获取签名
     * @param $baseUrl
     * @param $paramUrl
     * @return string
     */
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