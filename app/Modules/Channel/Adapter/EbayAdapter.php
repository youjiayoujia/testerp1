<?php
/**
 * Created by PhpStorm.
 * User: lilifeng
 * Date: 2016-06-01
 * Time: 10:53
 */

namespace App\Modules\Channel\Adapter;

use Tool;
use App\Models\OrderModel;

set_time_limit(1800);

class EbayAdapter implements AdapterInterface
{

    private $requestToken;
    private $devID;
    private $appID;
    private $certID;
    private $serverUrl;
    private $compatLevel;
    private $siteID;
    private $verb;


    public function __construct($config)
    {
        $this->requestToken = $config["requestToken"];
        $this->devID = $config["devID"];
        $this->appID = $config["appID"];
        $this->certID = $config["certID"];
        $this->serverUrl = 'https://api.ebay.com/ws/api.dll';
        $this->compatLevel = '745';

    }


    public function listOrders($startDate, $endDate, $status = [], $perPage = 10)
    {
        $returnOrders = [];
        $this->siteID = 0;
        $this->verb = 'GetOrders';
        $page = 1;
        $hasOrder = true;
        foreach ($status as $OrderStatus) {
            while ($hasOrder) {
                $requestXmlBody = $this->getListOrdersXml($startDate, $endDate, $OrderStatus, $page);
                $result = $this->sendHttpRequest($requestXmlBody);
                $response = simplexml_load_string($result);
                if (isset($response->OrderArray->Order) && !empty($response->OrderArray->Order)) {
                    $orders = $response->OrderArray->Order;
                    foreach ($orders as $order) {

                        $reurnOrder = $this->parseOrder($order);
                        if ($reurnOrder) {
                            $returnOrders[] = $reurnOrder;
                        }

                    }
                    $page++;
                } else {
                    var_dump($response);
                    $hasOrder = false;
                }

            }
        }

        return $returnOrders;
    }


    public function getListOrdersXml($startDate, $endDate, $OrderStatus, $page)
    {
        $returnMustBe = 'OrderArray.Order.OrderID,';
        $returnMustBe .= 'OrderArray.Order.ShippingAddress.Name,';
        $returnMustBe .= 'OrderArray.Order.ShippingAddress.Street1,';
        $returnMustBe .= 'OrderArray.Order.ShippingAddress.Street2,';
        $returnMustBe .= 'OrderArray.Order.ShippingAddress.CityName,';
        $returnMustBe .= 'OrderArray.Order.ShippingAddress.StateOrProvince,';
        $returnMustBe .= 'OrderArray.Order.ShippingAddress.Country,';
        $returnMustBe .= 'OrderArray.Order.ShippingAddress.CountryName,';
        $returnMustBe .= 'OrderArray.Order.ShippingAddress.Phone,';
        $returnMustBe .= 'OrderArray.Order.ShippingAddress.PostalCode,';
        $returnMustBe .= 'OrderArray.Order.CheckoutStatus.LastModifiedTime,';
        $returnMustBe .= 'OrderArray.Order.CheckoutStatus.Status,';
        $returnMustBe .= 'OrderArray.Order.CheckoutStatus.eBayPaymentStatus,';
        $returnMustBe .= 'OrderArray.Order.BuyerCheckoutMessage,';
        $returnMustBe .= 'OrderArray.Order.ExternalTransaction.ExternalTransactionID,';
        $returnMustBe .= 'OrderArray.Order.ShippingDetails.SellingManagerSalesRecordNumber,';
        $returnMustBe .= 'OrderArray.Order.Total,';
        $returnMustBe .= 'OrderArray.Order.OrderStatus,';
        $returnMustBe .= 'OrderArray.Order.PaymentMethods,';
        $returnMustBe .= 'OrderArray.Order.CreatedTime,';
        $returnMustBe .= 'OrderArray.Order.BuyerUserID,';
        $returnMustBe .= 'OrderArray.Order.PaidTime,';
        $returnMustBe .= 'OrderArray.Order.ShippedTime,';
        $returnMustBe .= 'OrderArray.Order.TransactionArray.Transaction.Buyer.Email,';
        $returnMustBe .= 'OrderArray.Order.TransactionArray.Transaction.Item.ItemID,';
        $returnMustBe .= 'OrderArray.Order.TransactionArray.Transaction.Item.SKU,';
        $returnMustBe .= 'OrderArray.Order.TransactionArray.Transaction.Item.Site,';
        $returnMustBe .= 'OrderArray.Order.TransactionArray.Transaction.Item.Title,';
        $returnMustBe .= 'OrderArray.Order.TransactionArray.Transaction.Variation.SKU,';
        $returnMustBe .= 'OrderArray.Order.TransactionArray.Transaction.Variation.VariationTitle,';
        $returnMustBe .= 'OrderArray.Order.TransactionArray.Transaction.Variation.VariationViewItemURL,';
        $returnMustBe .= 'OrderArray.Order.TransactionArray.Transaction.QuantityPurchased,';
        $returnMustBe .= 'OrderArray.Order.TransactionArray.Transaction.Variation.VariationSpecifics.NameValueList,';//广告属性
        $returnMustBe .= 'OrderArray.Order.TransactionArray.Transaction.Variation.VariationViewItemURL,';//广告地址
        $returnMustBe .= 'OrderArray.Order.TransactionArray.Transaction.ShippingDetails.SellingManagerSalesRecordNumber,';
        $returnMustBe .= 'OrderArray.Order.TransactionArray.Transaction.TransactionID,';
        $returnMustBe .= 'OrderArray.Order.TransactionArray.Transaction.TransactionPrice,';
        $returnMustBe .= 'OrderArray.Order.ShippingServiceSelected.ShippingService,';
        $returnMustBe .= 'OrderArray.Order.ShippingServiceSelected.ShippingServiceCost,';
        $returnMustBe .= 'PageNumber,';
        $returnMustBe .= 'PaginationResult.TotalNumberOfEntries,';
        $returnMustBe .= 'PaginationResult.TotalNumberOfPages';
        $requestXmlBody = '<?xml version="1.0" encoding="utf-8">' . "\n";
        $requestXmlBody .= '<GetOrdersRequest xmlns="urn:ebay:apis:eBLBaseComponents">';
        $requestXmlBody .= '<DetailLevel>ReturnAll</DetailLevel>';
        $requestXmlBody .= '<RequesterCredentials>';
        $requestXmlBody .= '<eBayAuthToken>' . $this->requestToken . '</eBayAuthToken>';
        $requestXmlBody .= '</RequesterCredentials>';
        $requestXmlBody .= '<ErrorLanguage>zh_CN</ErrorLanguage>';
        //$requestXmlBody.= '<MessageID>' . $MessageID . '</MessageID>';
        $requestXmlBody .= '<OutputSelector>' . $returnMustBe . '</OutputSelector>';
        $requestXmlBody .= '<Version>745</Version>';
        $requestXmlBody .= '<WarningLevel>High</WarningLevel>';
        $requestXmlBody .= '<IncludeFinalValueFee>false</IncludeFinalValueFee>';
        $requestXmlBody .= '<ModTimeFrom>' . $startDate . '</ModTimeFrom>';
        $requestXmlBody .= '<ModTimeTo>' . $endDate . '</ModTimeTo>';
        $requestXmlBody .= '<OrderRole>Seller</OrderRole>';
        $requestXmlBody .= '<OrderStatus>' . $OrderStatus . '</OrderStatus>';
        $requestXmlBody .= '<Pagination>';
        $requestXmlBody .= '<EntriesPerPage>100</EntriesPerPage>';
        $requestXmlBody .= '<PageNumber>' . $page . '</PageNumber>';
        $requestXmlBody .= '</Pagination>';
        $requestXmlBody .= '</GetOrdersRequest>';
        return $requestXmlBody;
    }

    public function getOrder($orderID)
    {
        return $orderID;
    }

    public function returnTrack()
    {
        return '1';
    }


    public function parseOrder($order)
    {

        $reurnOrder = array();
        $attr = $order->Total->attributes();
        $currencyID = (string)$attr['currencyID'];
        $isOrderStatus = $order->OrderStatus;
        $payMentStatus = $order->CheckoutStatus->eBayPaymentStatus;
        $paidTime = (string)$order->PaidTime;
        $ShippedTime = (string)$order->ShippedTime;
        if (!empty($ShippedTime)) {
            return false; //这个已经发货了吧
        }
        if (empty($paidTime)) {
            $paidTime = '';
        } else {
            $paidTime = date('Y-m-d H:i:s', strtotime($paidTime));
        }


        //121864765676-1639850594002
        $thisOrder = orderModel::where(['channel_ordernum' => (string)$order->OrderID])->where('status', '!=', 'UNPAID')->first();     //获取详情之前 进行判断是否存在 状态是未付款还是的继续

        if ($thisOrder) {
            return false;
        }
        /*  if((string)$order->OrderID=='121864765676-1639850594002'){
              $paidTime ='2016-06-02 09:00:00';
              echo '121864765676-1639850594002';
          }*/

        if (($isOrderStatus == 'Completed' && $payMentStatus == 'NoPaymentFailure') || !empty($paidTime)) {
            //正常订单
            $reurnOrder['status'] = 'PAID';
        } else {
            //未付款订单
            $reurnOrder['status'] = 'UNPAID';//没有付款的
        }

        $reurnOrder['currency'] = (string)$currencyID;
        $reurnOrder['channel_ordernum'] = (string)$order->OrderID;
        $reurnOrder['amount'] = (float)$order->Total;
        $reurnOrder['amount_shipping'] = (float)$order->ShippingServiceSelected->ShippingServiceCost;
        $reurnOrder['email'] = '';
        $reurnOrder['payment'] = (string)$order->PaymentMethods;
        $reurnOrder['shipping'] = (string)$order->ShippingServiceSelected->ShippingService;
        $reurnOrder['shipping_firstname'] = (string)$order->ShippingAddress->Name;
        $reurnOrder['shipping_lastname'] = '';
        $reurnOrder['shipping_address'] = (string)$order->ShippingAddress->Street1;
        $reurnOrder['shipping_address1'] = (string)$order->ShippingAddress->Street2;
        $reurnOrder['shipping_city'] = (string)$order->ShippingAddress->CityName;
        $reurnOrder['shipping_state'] = (string)$order->ShippingAddress->StateOrProvince;
        $reurnOrder['shipping_country'] = (string)$order->ShippingAddress->Country;
        //$reurnOrder['shipping_country_name'] =$order->ShippingAddress->CountryName;  //国家名字
        $reurnOrder['shipping_zipcode'] = (string)$order->ShippingAddress->PostalCode;
        $reurnOrder['shipping_phone'] = (string)$order->ShippingAddress->Phone;
        $reurnOrder['transaction_number'] = (string)$order->ExternalTransaction->ExternalTransactionID;
        $reurnOrder['payment_date'] = $paidTime;//支付时间
        $reurnOrder['aliexpress_loginId'] = (string)$order->BuyerUserID;
        $reurnOrder['remark'] = isset($order->BuyerCheckoutMessage)?(string)$order->BuyerCheckoutMessage:'';
        if (isset($order->TransactionArray->Transaction[0])) {
            foreach ($order->TransactionArray->Transaction as $sku) {
                $reurnOrder['email'] = (string)$sku->Buyer->Email == 'Invalid Request' ? '' : (string)$sku->Buyer->Email;
                $items = $this->parseItem($sku, $reurnOrder['currency'], $reurnOrder['channel_ordernum']);
                foreach ($items as $item) {
                    $item['currency'] = $reurnOrder['currency'];
                    $item['channel_order_id'] = $reurnOrder['channel_ordernum'];
                    $reurnOrder['items'][] = $item;
                }

            }
        } else {
            $reurnOrder['email'] = (string)$order->TransactionArray->Transaction->Buyer->Email == 'Invalid Request' ? '' : (string)$order->TransactionArray->Transaction->Buyer->Email;
            $items = $this->parseItem($order->TransactionArray->Transaction, $reurnOrder['currency'], $reurnOrder['channel_ordernum']);
            foreach ($items as $item) {
                $item['currency'] = $reurnOrder['currency'];
                $item['channel_order_id'] = $reurnOrder['channel_ordernum'];
                $reurnOrder['items'][] = $item;
            }

        }
        return $reurnOrder;
    }


    public function parseItem($Transaction)
    {
        $items = [];
        $remark = '';
        if (isset($Transaction->Variation->SKU)) {
            $channel_sku = $Transaction->Variation->SKU;

            if (isset($Transaction->Variation->VariationSpecifics->NameValueList[0])) {

                foreach ($Transaction->Variation->VariationSpecifics->NameValueList as $NameValueList) {
                    $remark = $NameValueList->Name . ':' . $NameValueList->Value . ' |' . $remark;
                }
            } else {
                $remark = $Transaction->Variation->VariationSpecifics->NameValueList->Name . ':' . $Transaction->Variation->VariationSpecifics->NameValueList->Value;
            }
        } else {
            $channel_sku = $Transaction->Item->SKU;
        }
        $erpSku = Tool::filter_sku((string)$channel_sku, 1); //根据账号的sku解析设定
        $allSkuNum = $erpSku['skuNum'];
        unset($erpSku['skuNum']);
        foreach ($erpSku as $sku) {
            $skuArray = [];
            $skuArray['channel_sku'] = (string)$channel_sku;
            $skuArray['sku'] = $sku['erpSku'];
            $skuArray['price'] = floatval($Transaction->TransactionPrice) / $allSkuNum;
            $skuArray['quantity'] = intval($Transaction->QuantityPurchased) * $sku['qty'];
            $skuArray['orders_item_number'] = (string)$Transaction->Item->ItemID;
            $skuArray['transaction_id'] = (string)$Transaction->TransactionID;
            $skuArray['remark'] = (string)$remark;
            $items[] = $skuArray;
        }
        return $items;


    }

    /**获取Ebay可用站点
     * @return array|bool
     */
    public function getEbaySite()
    {
        $return = [];
        $xml = '<DetailName>SiteDetails</DetailName>';
        $response = (array)$this->buildEbayBody($xml, 'GeteBayDetails', 0);
        if (isset($response['SiteDetails'])) {
            foreach ($response['SiteDetails'] as $key => $Site) {
                $return[$key]['site'] = (string)$Site->Site;
                $return[$key]['site_id'] = (int)$Site->SiteID;
                $return[$key]['detail_version'] = (int)$Site->DetailVersion;
            }

        } else {
            return false;
        }
        return $return;

    }

    /** 获取ebay站点的退货政策
     * @param $site 站点
     * @return array|bool
     */
    public function getEbayReturnPolicy($site)
    {
        $return = [];
        $xml = '<DetailName>ReturnPolicyDetails</DetailName>';
        $response = $this->buildEbayBody($xml, 'GeteBayDetails', $site);
        if ($response->Ack == 'Success') {
            if (isset($response->ReturnPolicyDetails->ReturnsWithin)) {
                $returnwishin_arr=[];
                foreach ($response->ReturnPolicyDetails->ReturnsWithin as $key=> $returnwishin) {
                    $returnwishin_arr[] = (string)$returnwishin->ReturnsWithinOption;
                }
                $return['returns_with_in'] = json_encode($returnwishin_arr);
            }
            if (isset($response->ReturnPolicyDetails->ReturnsAccepted)) {
                $returnaccept_arr=[];
                foreach ($response->ReturnPolicyDetails->ReturnsAccepted as $key=>$returnaccept) {
                    $returnaccept_arr[] = (string)$returnaccept->ReturnsAcceptedOption;
                }
                $return['returns_accepted'] = json_encode($returnaccept_arr);
            }


            if (isset($response->ReturnPolicyDetails->ShippingCostPaidBy)) {
                $shipcost_arr=[];
                foreach ($response->ReturnPolicyDetails->ShippingCostPaidBy as  $shipcost) {
                    $shipcost_arr[] = (string)$shipcost->ShippingCostPaidByOption;
                }
                $return['shipping_costpaid_by'] = json_encode($shipcost_arr);
            }

            if (isset($response->ReturnPolicyDetails->Refund)) {
                $refund_arr=[];
                foreach ($response->ReturnPolicyDetails->Refund as  $refund) {
                    $refund_arr[]=(string)$refund->RefundOption;
                }
                $return['refund'] = json_encode($refund_arr);
            }

           return $return;


        } else {
            return false;
        }

    }

    /**获取ebay对应站点国内国际运输方式
     * @param $site
     * @return array
     */
    public function getEbayShipping($site){
        $return = [];
        $xml = '<DetailName>ShippingServiceDetails</DetailName>';
        $response = $this->buildEbayBody($xml, 'GeteBayDetails', $site);
        if ($response->Ack == 'Success') {
            $i=0;
            foreach($response->ShippingServiceDetails as $shipping){
                $return[$i]['description']=(string)$shipping->Description;
                $return[$i]['international_service']=((string)$shipping->InternationalService=='true')?1:2; //1为国际 2为国内
                $return[$i]['shipping_service']=(string)$shipping->ShippingService;
                $return[$i]['shipping_service_id']=(int)$shipping->ShippingServiceID;
                $return[$i]['shipping_time_max']=(int)$shipping->ShippingTimeMax;
                $return[$i]['shipping_time_min']=(int)$shipping->ShippingTimeMin;
                $return[$i]['valid_for_selling_flow']=((string)$shipping->ValidForSellingFlow=='true')?1:2; //1 api可以使用 2 api不可使用
                $return[$i]['shipping_category']=(string)$shipping->ShippingCategory;
                $return[$i]['shipping_carrier']=isset($shipping->ShippingCarrier)?(string)$shipping->ShippingCarrier:'';
                $i++;
            }
        }else{
            return false;
        }

        return $return;

    }









    public function  buildEbayBody($xml, $call, $site = 0)
    {
        $this->siteID = $site;
        $this->verb = $call;
        $requestXmlBody = '<?xml version="1.0" encoding="utf-8"?><' . $call . 'Request xmlns="urn:ebay:apis:eBLBaseComponents">';
        $requestXmlBody .= $xml;
        $requestXmlBody .= '<RequesterCredentials><eBayAuthToken>' . $this->requestToken . '</eBayAuthToken></RequesterCredentials></' . $call . 'Request>';
        $result = $this->sendHttpRequest($requestXmlBody);
        $response = simplexml_load_string($result);
        return $response;

    }


    private function buildEbayHeaders()
    {
        $headers = array(
            'Content-type: text/xml',
            'X-EBAY-API-COMPATIBILITY-LEVEL: ' . $this->compatLevel,
            'X-EBAY-API-DEV-NAME: ' . $this->devID,
            'X-EBAY-API-APP-NAME: ' . $this->appID,
            'X-EBAY-API-CERT-NAME: ' . $this->certID,
            'X-EBAY-API-CALL-NAME: ' . $this->verb,
            'X-EBAY-API-SITEID: ' . $this->siteID,
        );

        return $headers;
    }

    public function sendHttpRequest($requestBody)
    {
        $headers = $this->buildEbayHeaders();
        //print_r($headers);

        $connection = curl_init();

        curl_setopt($connection, CURLOPT_URL, $this->serverUrl);
        curl_setopt($connection, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($connection, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($connection, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($connection, CURLOPT_POST, 1);
        curl_setopt($connection, CURLOPT_POSTFIELDS, $requestBody);
        curl_setopt($connection, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($connection, CURLOPT_TIMEOUT, 200);
        $response = curl_exec($connection);
        curl_close($connection);
        return $response;
    }


    public function getMessages(){

    }
    public function sendMessages(){
        
    }


}