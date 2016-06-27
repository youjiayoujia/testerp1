<?php
namespace App\Modules\Channel\Adapter;

/**
 * Created by PhpStorm.
 * User: Vincent
 * Date: 16/5/17
 * Time: 下午2:54
 * modify:jiangdi 2016-6-27 11:13:39 增加获取亚马逊平台邮件 function
 */
use App\Models\Message\MessageModel;
use App\Models\Message\AccountModel;
use App\Models\Message\ListModel;
use App\Models\Message\PartModel;
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
        foreach (AccountModel::all() as $account) {
            $client = $this->getClient($account);
            $service = new Google_Service_Gmail($client);
            $user = 'me';
            $i = 0;
            $nextPageToken = null;
            do {
                $i += 1;
                $messages = $service->users_messages->listUsersMessages($user,
                    [
                        'labelIds' => ['INBOX', 'UNREAD'],
                        'pageToken' => $nextPageToken
                    ]
                );
                $nextPageToken = $messages->nextPageToken;
                //save list
                $messageList = new ListModel;
                $messageList->account_id = $account->id;
                $messageList->next_page_token = $messages->nextPageToken;
                $messageList->result_size_estimate = $messages->resultSizeEstimate;
                $messageList->count = count($messages);
                $messageList->save();

                foreach ($messages as $message) {
                    $messageNew = MessageModel::firstOrNew(['message_id' => $message->id]);
                    if ($messageNew->id == null) {
                        $messageContent = $service->users_messages->get($user, $message->id);
                        $messagePayload = $messageContent->getPayload();
                        $messageHeader = $this->parseMessageHeader($messagePayload->getHeaders());

                        $messageLabels = $messageContent->getLabelIds();
                        $messageNew->account_id = $account->id;
                        $messageNew->list_id = $messageList->id;
                        $messageNew->message_id = $messageContent->getId();
                        $messageNew->labels = serialize($messageLabels);
                        $messageNew->label = $messageLabels[0];
                        if (isset($messageHeader['From'])) {
                            $messageFrom = explode(' <', $messageHeader['From']);
                            if (count($messageFrom) > 1) {
                                $messageNew->from = $this->clearEmail(str_replace('>', '', $messageFrom[1]));
                                $messageNew->from_name = str_replace('"', '', $messageFrom[0]);
                            } else {
                                $messageNew->from = $this->clearEmail($messageHeader['From']);
                            }
                        }
                        if (isset($messageHeader['To'])) {
                            $messageTo = explode(' <', $messageHeader['To']);
                            if (count($messageTo) > 1) {
                                $messageNew->to = $this->clearEmail(str_replace('>', '', $messageTo[1]));
                            } else {
                                $messageNew->to = $this->clearEmail($messageHeader['To']);
                            }
                        }
                        $messageNew->date = isset($messageHeader['Date']) ? $messageHeader['Date'] : '';
                        $messageNew->subject = isset($messageHeader['Subject']) ? $messageHeader['Subject'] : '';
                        /*
                        //判断subject 是否有值
                        if($messageHeader['Subject']){
                            //截取两个规定字符之间的字符串
                            preg_match_all("|Message from(.*)via|U", $messageHeader['Subject'], $out,PREG_PATTERN_ORDER);
                        }
                        $messageNew->title_email = isset($out[0][0]) ?  $out[0][0] : '';
                        */

                        $messageNew->save();
                        $this->saveMessagePayload($service, $message->id, $messageNew->id, $messagePayload);
                        $messageNew->content = $messageNew->message_content;
                        $messageNew->save();
                        $this->info('Message #' . $messageNew->message_id . ' Received.');
                    }
                }
            } while ($nextPageToken != '');
        }
        
    }


    public function getClient($account)
    {
        $client = new Google_Client();
        $client->setScopes(implode(' ', array(
            Google_Service_Gmail::GMAIL_READONLY
        )));
        $client->setAuthConfig($account->secret);
        $client->setAccessType('offline');
        $client->setAccessToken($account->token);

        // Refresh the token if it's expired.
        if ($client->isAccessTokenExpired()) {
            $client->refreshToken($client->getRefreshToken());
            $account->token = $client->getAccessToken();
            $account->save();
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

    public function saveMessagePayload($service, $messageId, $messageNewId, $payload, $parentId = 0)
    {
        $messagePart = PartModel::firstOrNew([
            'message_id' => $messageNewId,
            'parent_id' => $parentId,
            'part_id' => $payload->getPartId()
        ]);
        $messagePart->message_id = $messageNewId;
        $messagePart->parent_id = $parentId;
        $messagePart->part_id = $payload->getPartId();
        $messagePart->mime_type = $payload->getMimeType();
        $messagePart->headers = serialize($payload->getHeaders());
        $messagePart->filename = $payload->getFilename();
        $messagePart->attachment_id = $payload->getBody()->getAttachmentId();
        $messagePart->body = $payload->getBody()->getData();
        $messagePart->save();

        if ($payload->getFilename()) {
            $attachment = $service->users_messages_attachments->get('me', $messageId, $messagePart->attachment_id);
            @file_put_contents(config('message.attachmentPath') . $messagePart->id . '_' . $messagePart->filename,
                Tool::base64Decode($attachment->data));
        }

        $mimeType = explode('/', $payload->getMimeType());
        if ($mimeType[0] == 'multipart') {
            foreach ($payload->getParts() as $part) {
                $this->saveMessagePayload($service, $messageId, $messageNewId, $part, $messagePart->id);
            }
        }
    }

}