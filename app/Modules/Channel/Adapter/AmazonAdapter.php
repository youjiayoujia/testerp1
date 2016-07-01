<?php
namespace App\Modules\Channel\Adapter;

/**
 * Created by PhpStorm.
 * User: Vincent
 * Date: 16/5/17
 * Time: 下午2:54
 * modify:jiangdi 2016-6-27 11:13:39 增加获取亚马逊平台邮件 function
 */

use App\Models\Channel\AccountModel;
use Google_Client;
use Google_Service_Gmail;
use Tool;

Class AmazonAdapter implements AdapterInterface
{
    private $serviceUrl;
    private $signatureVersion = '2';
    private $signatureMethod = 'HmacSHA256';
    private $version = '2013-09-01';
    private $config = [];
    private $messageConfig = [];

    public function __construct($config)
    {
        $this->serviceUrl = $config['serviceUrl'];
        unset($config['serviceUrl']);
        $this->config = array_merge($config);
        $this->config['SignatureVersion'] = $this->signatureVersion;
        $this->config['SignatureMethod'] = $this->signatureMethod;
        $this->config['Version'] = $this->version;
        $this->messageConfig['GmailSecret'] = $config['GmailSecret'];
        $this->messageConfig['GmailToken'] = $config['GmailToken'];
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
            'channel_ordernum' => $order->AmazonOrderId,
            'email' => $order->BuyerEmail,
            'amount' => $order->OrderTotal->Amount,
            'currency' => $order->OrderTotal->CurrencyCode,
            'status' => 'PAID',
            'payment' => $order->PaymentMethod,
            'shipping' => $order->ShipmentServiceLevelCategory,
            'shipping_firstname' => isset($shippingName[0]) ? $shippingName[0] : '',
            'shipping_lastname' => isset($shippingName[1]) ? $shippingName[1] : '',
            'shipping_address' => $order->ShippingAddress->AddressLine1,
            'shipping_address1' => $order->ShippingAddress->AddressLine2,
            'shipping_city' => $order->ShippingAddress->City,
            'shipping_state' => $order->ShippingAddress->StateOrRegion,
            'shipping_country' => $order->ShippingAddress->CountryCode,
            'shipping_zipcode' => $order->ShippingAddress->PostalCode,
            'shipping_phone' => $order->ShippingAddress->Phone,
            'payment_date' => $order->PurchaseDate,
            'create_time' => $order->PurchaseDate,
            'fulfill_by' => $order->FulfillmentChannel,
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
        $result = [
            'sku' => '',
            'channel_sku' => $orderItem->SellerSKU,
            'quantity' => $orderItem->QuantityOrdered,
            'price' => $orderItem->ItemPrice->Amount,
            'currency' => $orderItem->ItemPrice->CurrencyCode,
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

    public function getMessages()
    {
        // TODO: Implement getMessages() method.
            $client = $this->getClient($this->messageConfig);
            $service = new Google_Service_Gmail($client);
            $user = 'me';
            $i = 0;
            $j = 0; //统计信息条数
            $nextPageToken = null;
            $returnAry = [];
            do {
                $i += 1;
                $messages = $service->users_messages->listUsersMessages($user,
                    [
                        'labelIds' => ['INBOX', 'UNREAD'],
                        'pageToken' => $nextPageToken
                    ]
                );
                $nextPageToken = $messages->nextPageToken;
                foreach ($messages as $key => $message) {
                    $j += 1;
                    //1 获取邮件信息
                    $messageContent = $service->users_messages->get($user, $message->id);
                    $messagePayload = $messageContent->getPayload();
                    $messageHeader = $this->parseMessageHeader($messagePayload->getHeaders());
                    $messageLabels = $messageContent->getLabelIds();

                    $returnAry[$j]['message_id'] = $messageContent->getId();
                    $returnAry[$j]['labels'] = serialize($messageLabels);
                    $returnAry[$j]['label'] = $messageLabels[0];
                    $returnAry[$j]['body'] = $messageLabels[0];

                    if (isset($messageHeader['From'])) {
                        $messageFrom = explode(' <', $messageHeader['From']);
                        if (count($messageFrom) > 1) {
                            $returnAry[$j]['from'] = $this->clearEmail(str_replace('>', '', $messageFrom[1]));
                            $returnAry[$j]['from_name'] = str_replace('"', '', $messageFrom[0]);
                        } else {
                            $returnAry[$j]['from'] = $this->clearEmail($messageHeader['From']);
                        }
                    }
                    if (isset($messageHeader['To'])) {
                        $messageTo = explode(' <', $messageHeader['To']);
                        if (count($messageTo) > 1) {
                            $returnAry[$j]['to'] = $this->clearEmail(str_replace('>', '', $messageTo[1]));
                        } else {
                            $returnAry[$j]['to'] = $this->clearEmail($messageHeader['To']);
                        }
                    }
                    $returnAry[$j]['date'] = isset($messageHeader['Date']) ? $messageHeader['Date'] : '';
                    $returnAry[$j]['subject'] = isset($messageHeader['Subject']) ? $messageHeader['Subject'] : '';
                    /**
                     * 处理附件并获取content
                     */
                    $tempPayLoad = '';
                    $tempAttachment = '';
                    $this->getPayloadNew($tempPayLoad,$tempAttachment,$messagePayload,$service,$message);
                    $returnAry[$j]['content'] = $this->getMaillContent($tempPayLoad);
                    $returnAry[$j]['attachment'] = $tempAttachment;
                }
            } while ($nextPageToken != '');
        return $returnAry;
    }
    /**
     * 获取附件，上传附件
     * @param $data
     * @param $attachment
     * @param $payload
     * @param $service
     * @param $message
     */
    public function getPayloadNew(&$data,&$attachment,$payload,$service,$message){

        if($fileName = $payload->getFilename()){
            $extraFile = $service->users_messages_attachments->get('me', $message->id, $payload->getBody()->getAttachmentId());
            
            if(!is_dir(config('message.attachmentPath') .'/'.$message->id)){
                mkdir(config('message.attachmentPath') .'/'.$message->id,0777);
            }
            
            $FileAry = explode('.',$fileName); //拆分文件名
            $countSize = file_put_contents(config('message.attachmentPath') .$message->id . '/' . Tool::base64Encode($FileAry[0]).'.'.$FileAry[1], Tool::base64Decode($extraFile->data));
            if($countSize > 0){
                $attachmentInfo = [
                    'file_name' => Tool::base64Encode($FileAry[0]).'.'.$FileAry[1],
                    'file_path' => $message->id . '/'. Tool::base64Encode($FileAry[0]).'.'.$FileAry[1], //图片目录
                ];
            }
        }else{
            $attachmentInfo = '';
        }
        $data[] = [
            'mime_type'  => $payload->getMimeType(),
            'body'       => $payload->getBody()->getData(),
        ];
        if(!empty($attachmentInfo)){
            $attachment [] = $attachmentInfo;
        }
        $mimeType = explode('/', $payload->getMimeType());
        if ($mimeType[0] == 'multipart') {
            foreach ($payload->getParts() as $part) {
                $this->getPayloadNew($data,$attachment,$part,$service,$message);
            }
        }
    }

    /**
     * 获取邮件内容
     * @param $parts
     * @return mixed|string
     */
    public function getMaillContent($parts){
        $plainBody = '';
        foreach ($parts as $part){
            if($part['mime_type']== 'text/html'){
                $htmlBody = Tool::base64Decode($part['body']);
                $htmlBody=preg_replace("/<(\/?body.*?)>/si","",$htmlBody);
            }
            if ($part['mime_type'] == 'text/plain') {
                $plainBody .= nl2br(Tool::base64Decode($part['body']));
            }
        }
        $body = isset($htmlBody) && $htmlBody != '' ? $htmlBody : $plainBody;
        return $body;
    }
    
    public function getClient($account)
    {
        $client = new Google_Client();
        $client->setScopes(implode(' ', array(
            Google_Service_Gmail::GMAIL_READONLY
        )));
        $client->setAuthConfig($account['GmailSecret']);
        $client->setAccessType('offline');
        $client->setAccessToken($account['GmailToken']);

        // Refresh the token if it's expired.
        if ($client->isAccessTokenExpired()) {
            $client->refreshToken($client->getRefreshToken());
            $thisAccount = AccountModel::where('message_secret',$account['GmailSecret'])->first();
            $thisAccount->message_token = $client->getAccessToken();
            $thisAccount->save();
        }
        return $client;
    }

    public function parseMessageHeader($headers)
    {
        $result = [];

        foreach ($headers as $header) {
            $result[$header->getName()] = $header->getValue();
        }

        return $result;
    }

    public function clearEmail($email)
    {
        $email = str_replace('<', '', $email);
        $email = str_replace('>', '', $email);
        return $email;
    }
}