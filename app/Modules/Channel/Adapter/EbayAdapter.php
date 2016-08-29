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


    public function listOrders($startDate, $endDate, $status = [], $perPage = 10, $nextToken = '')
    {
        $returnOrders = [];
        $this->siteID = 0;
        $this->verb = 'GetOrders';
        $OrderStatus = $status[0];
        if (empty($nextToken)) {
            $nextToken = 1;
        }
        $requestXmlBody = $this->getListOrdersXml($startDate, $endDate, $OrderStatus, $perPage, $nextToken);
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
            $nextToken++;
        } else {
            //var_dump($response);
            $nextToken = '';
        }
        return ['orders' => $returnOrders, 'nextToken' => $nextToken];
    }


    public function getListOrdersXml($startDate, $endDate, $OrderStatus, $pageSizem, $page)
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

    /**
     * @param $tracking_info =[
     *              'IsUploadTrackingNumber' =>'' //true or false
     *              'ShipmentTrackingNumber'=>'' //追踪号
     *              'ShippingCarrierUsed'=>''//承运商
     *              'ShippedTime' =>'' //发货时间 date('Y-m-d\TH:i:s\Z')
     *              'ItemID' =>'' //商品id
     *              'TransactionID' =>'交易号'，
     * ]
     *
     * @return string
     */
    public function returnTrack($tracking_info)
    {
        $return = [];
        $xml = '';
        if ($tracking_info['IsUploadTrackingNumber']) { //需要上传追踪号
            $xml .= '<Shipment>';
            $xml .= '<ShipmentTrackingDetails>';
            $xml .= '<ShipmentTrackingNumber>' . $tracking_info['ShipmentTrackingNumber'] . '</ShipmentTrackingNumber>';
            $xml .= '<ShippingCarrierUsed>' . $tracking_info['ShippingCarrierUsed'] . '</ShippingCarrierUsed>';
            $xml .= '</ShipmentTrackingDetails>';
            $xml .= '<ShippedTime>' . $tracking_info['ShippedTime'] . '</ShippedTime>';
            $xml .= '</Shipment>';
        }
        $xml .= '<ItemID>' . $tracking_info['ItemID'] . '</ItemID>';
        $xml .= '<Shipped>true</Shipped>';
        $xml .= '<TransactionID>' . $tracking_info['TransactionID'] . '</TransactionID>';
        $result = $this->buildEbayBody($xml, 'CompleteSale');
        if ((string)$result->Ack == 'Success') {
            $return['status'] = true;
            $return['info'] = 'Success';
        } else {
            $return['status'] = false;
            $return['info'] = isset($result->LongMessage) ? (string)$result->LongMessage : 'error';
            //$return['info'] = '模拟标记失败';
        }
        return $return;
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
        /*   $thisOrder = orderModel::where(['channel_ordernum' => (string)$order->OrderID])->where('status', '!=', 'UNPAID')->first();     //获取详情之前 进行判断是否存在 状态是未付款还是的继续

           if ($thisOrder) {
               return false;
           }*/
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
        $reurnOrder['channel_listnum'] = isset($order->ShippingDetails->SellingManagerSalesRecordNumber) ? (string)$order->ShippingDetails->SellingManagerSalesRecordNumber : '';
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
        $reurnOrder['remark'] = isset($order->BuyerCheckoutMessage) ? (string)$order->BuyerCheckoutMessage : '';
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
                $returnwishin_arr = [];
                foreach ($response->ReturnPolicyDetails->ReturnsWithin as $key => $returnwishin) {
                    $returnwishin_arr[] = (string)$returnwishin->ReturnsWithinOption;
                }
                $return['returns_with_in'] = json_encode($returnwishin_arr);
            }
            if (isset($response->ReturnPolicyDetails->ReturnsAccepted)) {
                $returnaccept_arr = [];
                foreach ($response->ReturnPolicyDetails->ReturnsAccepted as $key => $returnaccept) {
                    $returnaccept_arr[] = (string)$returnaccept->ReturnsAcceptedOption;
                }
                $return['returns_accepted'] = json_encode($returnaccept_arr);
            }


            if (isset($response->ReturnPolicyDetails->ShippingCostPaidBy)) {
                $shipcost_arr = [];
                foreach ($response->ReturnPolicyDetails->ShippingCostPaidBy as $shipcost) {
                    $shipcost_arr[] = (string)$shipcost->ShippingCostPaidByOption;
                }
                $return['shipping_costpaid_by'] = json_encode($shipcost_arr);
            }

            if (isset($response->ReturnPolicyDetails->Refund)) {
                $refund_arr = [];
                foreach ($response->ReturnPolicyDetails->Refund as $refund) {
                    $refund_arr[] = (string)$refund->RefundOption;
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
    public function getEbayShipping($site)
    {
        $return = [];
        $xml = '<DetailName>ShippingServiceDetails</DetailName>';
        $response = $this->buildEbayBody($xml, 'GeteBayDetails', $site);
        if ($response->Ack == 'Success') {
            $i = 0;
            foreach ($response->ShippingServiceDetails as $shipping) {
                $return[$i]['description'] = (string)$shipping->Description;
                $return[$i]['international_service'] = ((string)$shipping->InternationalService == 'true') ? 1 : 2; //1为国际 2为国内
                $return[$i]['shipping_service'] = (string)$shipping->ShippingService;
                $return[$i]['shipping_service_id'] = (int)$shipping->ShippingServiceID;
                $return[$i]['shipping_time_max'] = (int)$shipping->ShippingTimeMax;
                $return[$i]['shipping_time_min'] = (int)$shipping->ShippingTimeMin;
                $return[$i]['valid_for_selling_flow'] = ((string)$shipping->ValidForSellingFlow == 'true') ? 1 : 2; //1 api可以使用 2 api不可使用
                $return[$i]['shipping_category'] = (string)$shipping->ShippingCategory;
                $return[$i]['shipping_carrier'] = isset($shipping->ShippingCarrier) ? (string)$shipping->ShippingCarrier : '';
                $i++;
            }
        } else {
            return false;
        }

        return $return;

    }

    /** 获取对应站点分类
     * @param $level 分类级别
     * @param string $categoryParent 上级分类
     * @param int $site 站点
     * @return array|bool
     */
    public function getEbayCategoryList($level, $categoryParent = '', $site = 0)
    {
        $return = [];
        $xml = '<DetailLevel>ReturnAll</DetailLevel>';
        $xml .= '<LevelLimit>' . $level . '</LevelLimit>';
        if (!empty($categoryParent)) {
            $xml .= '<CategoryParent>' . $categoryParent . '</CategoryParent>';
        }
        $xml .= '<CategorySiteID>' . $site . '</CategorySiteID>';
        $response = $this->buildEbayBody($xml, 'GetCategories', $site);
        if ($response->Ack == 'Success') {
            foreach ($response->CategoryArray->Category as $category) {
                $data = [];
                $data['category_id'] = (int)$category->CategoryID;
                $data['best_offer'] = isset($category->BestOfferEnabled) ? (string)$category->BestOfferEnabled : '';
                $data['auto_pay'] = isset($category->AutoPayEnabled) ? (string)$category->AutoPayEnabled : '';
                $data['category_level'] = (int)$category->CategoryLevel;
                $data['category_name'] = (string)$category->CategoryName;
                $data['category_parent_id'] = (int)$category->CategoryParentID;
                $data['leaf_category'] = isset($category->LeafCategory) ? (string)$category->LeafCategory : '';
                $data['site'] = $site;
                $return[] = $data;
            }
        } else {
            return false;
        }
        return $return;

    }

    public function getEbayCondition($category_id, $site)
    {
        $return = [];
        $xml = '<DetailLevel>ReturnAll</DetailLevel>
                <FeatureID>ConditionEnabled</FeatureID>
                <FeatureID>ConditionValues</FeatureID>
                <FeatureID>ItemSpecificsEnabled</FeatureID>
                <FeatureID>VariationsEnabled</FeatureID>
                <FeatureID>UPCEnabled</FeatureID>
                <FeatureID>EANEnabled</FeatureID>
                <FeatureID>ISBNEnabled</FeatureID>';
        $xml .= '<CategoryID>' . $category_id . '</CategoryID>';
        $xml .= '<CategorySiteID>' . $site . '</CategorySiteID>';
        $response = $this->buildEbayBody($xml, 'GetCategoryFeatures', $site);
        if ($response->Ack == 'Success') {
            $conditions = $response->Category->ConditionValues->Condition;
            foreach ($conditions as $condition) {
                $data = [];
                $data['condition_id'] = (int)$condition->ID;
                $data['condition_name'] = (string)$condition->DisplayName;
                $data['category_id'] = $category_id;
                $data['site'] = $site;
                $data['is_variations'] = isset($response->Category->VariationsEnabled) ? (string)$response->Category->VariationsEnabled : '';
                $data['is_condition'] = isset($response->Category->ConditionEnabled) ? (string)$response->Category->ConditionEnabled : '';
                $data['is_upc'] = isset($response->Category->UPCEnabled) ? (string)$response->Category->UPCEnabled : '';
                $data['is_ean'] = isset($response->Category->EANEnabled) ? (string)$response->Category->EANEnabled : '';
                $data['is_isbn'] = isset($response->Category->ISBNEnabled) ? (string)$response->Category->ISBNEnabled : '';
                $data['last_update_time'] = date('Y-m-d H:i:s', time());
                $return[] = $data;
            }
        } else {
            return false;
        }

        return $return;

    }

    public function getEbayCategorySpecifics($category_id, $site)
    {
        $return = [];
        $xml = '<CategorySpecific><CategoryID>' . $category_id . '</CategoryID></CategorySpecific>';
        $response = $this->buildEbayBody($xml, 'GetCategorySpecifics', $site);
        if ($response->Ack == 'Success') {
            foreach ($response->Recommendations->NameRecommendation as $v) {
                $data = [];
                $data['name'] = (string)$v->Name;
                $data['value_type'] = isset($v->ValidationRules->ValueType) ? (string)$v->ValidationRules->ValueType : '';
                $data['min_values'] = isset($v->ValidationRules->MinValues) ? (string)$v->ValidationRules->MinValues : '';
                $data['max_values'] = isset($v->ValidationRules->MaxValues) ? (string)$v->ValidationRules->MaxValues : '';
                $data['selection_mode'] = isset($v->ValidationRules->SelectionMode) ? (string)$v->ValidationRules->SelectionMode : '';
                $data['variation_specifics'] = isset($v->ValidationRules->VariationSpecifics) ? (string)$v->ValidationRules->VariationSpecifics : '';
                $data['last_update_time'] = date('Y-m-d H:i:s', time());
                $specific_values = array();
                foreach ($v->ValueRecommendation as $i_v) {
                    $specific_values[] = (string)$i_v->Value;
                }
                $data['specific_values'] = serialize($specific_values);
                $data['category_id'] = $category_id;
                $data['site'] = $site;
                $return[] = $data;
            }
        } else {
            return false;
        }
        return $return;
    }


    /** 获取该账号近一个月的Feedback
     * @param int $site
     */
    public function GetFeedback($site = 0)
    {
        $return = [];
        $pageSize = 100;
        $end = 10;
        $is_break = false;
        for ($page = 1; $page < $end; $page++) {
            $xml = '<DetailLevel>ReturnAll</DetailLevel>
			  <FeedbackType>FeedbackReceivedAsSeller</FeedbackType>
              <CommentType>Positive</CommentType>
			  <CommentType>Negative</CommentType>
			  <CommentType>Neutral</CommentType>
			  <OutputSelector>FeedbackDetailArray</OutputSelector>
			  <OutputSelector>PaginationResult</OutputSelector>
			  <WarningLevel>High</WarningLevel>
			  <Pagination><EntriesPerPage>' . $pageSize . '</EntriesPerPage>
		      <PageNumber>' . $page . '</PageNumber></Pagination>';
            $response = $this->buildEbayBody($xml, 'GetFeedback', $site);
            if ($response->Ack == 'Success') {
                $end = (int)$response->PaginationResult->TotalNumberOfPages;
                foreach ($response->FeedbackDetailArray->FeedbackDetail as $FeedbackDetail) {
                    $detail = [];
                    $detail['feedback_id'] = (string)$FeedbackDetail->FeedbackID;
                    $detail['commenting_user'] = (string)$FeedbackDetail->CommentingUser;
                    $detail['commenting_user_score'] = (int)$FeedbackDetail->CommentingUserScore;
                    $detail['comment_text'] = (string)$FeedbackDetail->CommentText;
                    $detail['comment_type'] = (string)$FeedbackDetail->CommentType;
                    $detail['ebay_item_id'] = (string)$FeedbackDetail->ItemID;
                    $detail['transaction_id'] = (string)$FeedbackDetail->TransactionID;
                    $detail['comment_time'] = date('Y-m-d H:i:s', strtotime($FeedbackDetail->CommentTime));
                    $return[] = $detail;
                    if (time() - strtotime($detail['comment_time']) > 30 * 24 * 60 * 60) { //超过30
                        $is_break = true;
                        break;
                    }
                }
            } else {
                break;
            }
            if ($is_break) {
                break;
            }
        }
        return $return;

    }

    /** 评价订单API
     * @param $data
     * @param string $type Positive 好评 Neutral中评 Negative 差评
     * @param string $text 评价内容
     * @param int $site 站点
     * @return bool
     */
    public function LeaveFeedback($data, $type = 'Positive', $text = 'Good buyer,prompt payment,nice to deal with!', $site = 0)
    {
        $xml = '<CommentText>' . $text . '</CommentText>';
        $xml .= '<CommentType>' . $type . '</CommentType>';
        $xml .= '<ItemID>' . $data['item_id'] . '</ItemID>';
        $xml .= '<TransactionID>' . $data['transaction_id'] . '</TransactionID>';
        $xml .= '<TargetUser>' . $data['target_user'] . '</TargetUser>';
        $response = $this->buildEbayBody($xml, 'LeaveFeedback', $site);
        if ($response->Ack == 'Success') {
            return true;
        } else {
            return false;
        }

    }

    /** 获取在线的listting
     * @param int $page
     * @param int $pageSize
     * @param int $site
     * @return array|bool
     */
    public function getOnlineProduct($page = 1, $pageSize = 200, $site = 0)
    {
        $return = [];
        $xml = '';
        //  $xml .= '<DetailLevel>ReturnAll</DetailLevel>';
        $xml .= '<ErrorLanguage>en_US</ErrorLanguage>';
        $xml .= '<MessageID>1000</MessageID>';
        $xml .= '<OutputSelector>ActiveList</OutputSelector>';
        $xml .= '<OutputSelector>ShippingDetails</OutputSelector>';
        $xml .= '<Version>' . $this->compatLevel . '</Version>';
        $xml .= '<ActiveList>';
        $xml .= '<Pagination>';
        $xml .= '<EntriesPerPage>' . $pageSize . '</EntriesPerPage>';
        $xml .= '<PageNumber>' . $page . '</PageNumber>';
        $xml .= '</Pagination>';
        $xml .= '</ActiveList>';
        $xml .= '<HideVariations>false</HideVariations>';
        $response = $this->buildEbayBody($xml, 'GetMyeBaySelling', $site);
        if(isset($response->ActiveList->PaginationResult->TotalNumberOfPages)&&($page>(int)$response->ActiveList->PaginationResult->TotalNumberOfPages)){
            return false;
        }
        if (isset($response->ActiveList->ItemArray->Item) && !empty($response->ActiveList->ItemArray->Item)) {
            foreach ($response->ActiveList->ItemArray->Item as $item) {
                $return[] = (string)$item->ItemID;
            }
            return $return;
        } else {
            return false;
        }
    }

    public function getSellerEvents($start_time='',$end_time='',$site = 0){
        $return = [];
        $xml = '';
        $xml .=' <EndTimeFrom>'.$start_time.'T00:00:00.000Z</EndTimeFrom><EndTimeTo>'.$end_time.'T00:00:00.000Z</EndTimeTo>';
        $response = $this->buildEbayBody($xml, 'GetSellerEvents', $site);
        if(isset($response->ItemArray)&&!empty($response->ItemArray)&&$response->Ack == 'Success'){
            foreach($response->ItemArray->Item as $item){
                $return[] =$item->ItemID;
            }
            return $return;
        }else{
            return false;
        }
    }

    /** 获取item 详情
     * @param $itemId
     * @param int $site
     * @return array|bool
     */
    public function getProductDetail($itemId, $site = 0)
    {
        $return = [];
        //$itemId = 122059295599;//122077657433	;
        $xml = '';
        $xml .= '<ItemID>' . $itemId . '</ItemID>';
        $xml .= '<DetailLevel>ReturnAll</DetailLevel>';
        $xml .= '<IncludeItemSpecifics>true</IncludeItemSpecifics>';
        $response = $this->buildEbayBody($xml, 'GetItem', $site);
        if ($response->Ack == 'Success') {
            $list_info = [];
            $sku_info = [];
            $list_info['item_id'] = (string)$response->Item->ItemID;
            $list_info['currency'] = (string)$response->Item->Currency;
            $list_info['country'] = (string)$response->Item->Country;
            $list_info['start_time'] = date('Y-m-d H:i:s',strtotime((string)$response->Item->ListingDetails->StartTime));
            $list_info['view_item_url'] = (string)$response->Item->ListingDetails->ViewItemURL;
            $list_info['listing_duration'] = (string)$response->Item->ListingDuration;
            $list_info['listing_type'] = (string)$response->Item->ListingType;
            $list_info['location'] = (string)$response->Item->Location;
            $list_info['postal_code'] = (string)$response->Item->PostalCode;
            $list_info['payment_methods'] = (string)$response->Item->PaymentMethods;
            $list_info['paypal_email_address'] = (string)$response->Item->PayPalEmailAddress;
            $list_info['primary_category'] = (string)$response->Item->PrimaryCategory->CategoryID;
            $list_info['secondary_category'] = (string)$response->Item->SecondaryCategory->CategoryID;
            $list_info['private_listing'] = (string)$response->Item->PrivateListing;
            $list_info['dispatch_time_max'] = (string)$response->Item->DispatchTimeMax;
            $list_info['start_price'] = (float)$response->Item->StartPrice;
            $list_info['quantity'] = (int)$response->Item->Quantity-(int)$response->Item->SellingStatus->QuantitySold;
            $list_info['reserve_price'] = (float)$response->Item->ReservePrice;
            $list_info['buy_it_now_price'] = (float)$response->Item->BuyItNowPrice;
            $list_info['title'] = (string)$response->Item->Title;
            $list_info['sub_title'] = (string)$response->Item->SubTitle;
            $list_info['sku'] = (string)$response->Item->SKU;
            $list_info['site_name'] = (string)$response->Item->Site;
            $list_info['site'] = config('ebaysite.site_name_id')[$list_info['site_name']];
            $list_info['quantity_sold'] = (int)$response->Item->SellingStatus->QuantitySold;
            $list_info['store_category_id'] = (string)$response->Item->Storefront->StoreCategoryID;

            //ConditionID
            $list_info['condition_id'] = (string)$response->Item->ConditionID;
            $list_info['condition_description'] = (string)$response->Item->ConditionDescription;

            $list_info['picture_details'] = isset($response->Item->PictureDetails->PictureURL) ? json_encode((array)$response->Item->PictureDetails->PictureURL) : '';
            //ItemSpecifics
            $ItemSpecifics = isset($response->Item->ItemSpecifics->NameValueList) ? $response->Item->ItemSpecifics->NameValueList : '';
            $item_specifics = [];
            if (!empty($ItemSpecifics)) {
                foreach ($ItemSpecifics as $specifics) {
                    $item_specifics[(string)$specifics->Name] = (string)$specifics->Value;
                }
            }
            unset($ItemSpecifics);


            $list_info['item_specifics'] = json_encode($item_specifics);
            unset($item_specifics);


            $Variations = isset($response->Item->Variations->Variation) ? $response->Item->Variations->Variation : '';
            if (!empty($Variations)) {
                $i = 0;
                foreach ($Variations as $variation) {
                    $sku_info[$i]['sku'] = (string)$variation->SKU;
                    $sku_info[$i]['start_price'] = (float)$variation->StartPrice;
                    $sku_info[$i]['quantity'] = (int)$variation->Quantity-(int)$variation->SellingStatus->QuantitySold;
                    $sku_info[$i]['erp_sku'] = (string)$variation->SKU;
                    $sku_info[$i]['quantity_sold'] = isset($variation->SellingStatus->QuantitySold) ? (int)$variation->SellingStatus->QuantitySold : 0;
                    $sku_info[$i]['item_id'] = (string)$response->Item->ItemID;
                    $sku_info[$i]['start_time'] = date('Y-m-d H:i:s',strtotime((string)$response->Item->ListingDetails->StartTime));
                    $variation_specifics = [];
                    if (isset($variation->VariationSpecifics)) {
                        if (isset($variation->VariationSpecifics->NameValueList[0])) {
                            foreach ($variation->VariationSpecifics->NameValueList as $nameList) {
                                $variation_specifics[(string)$nameList->Name] = (string)$nameList->Value;
                            }
                        } else {
                            $variation_specifics[(string)$variation->VariationSpecifics->NameValueList->Name] = (string)$variation->VariationSpecifics->NameValueList->Value;
                        }
                    }
                    if (isset($variation->VariationProductListingDetails)) {
                        $VariationProductListingDetails = (array)$variation->VariationProductListingDetails;
                        foreach ($VariationProductListingDetails as $key => $value) {
                            $variation_specifics[(string)$key] = (string)$value;
                        }
                    }
                    $sku_info[$i]['variation_specifics'] = $variation_specifics;
                    $i++;
                }
            } else {
                $sku_info[0]['sku'] = (string)$response->Item->SKU;
                $sku_info[0]['start_price'] = (float)$response->Item->StartPrice;;
                $sku_info[0]['quantity'] = (int)$response->Item->Quantity-(int)$response->Item->SellingStatus->QuantitySold;
                $sku_info[0]['erp_sku'] = (string)$response->Item->SKU;;
                $sku_info[0]['quantity_sold'] = (int)$response->Item->SellingStatus->QuantitySold;
                $sku_info[0]['item_id'] = (string)$response->Item->ItemID;
                $sku_info[0]['start_time'] = date('Y-m-d H:i:s',strtotime((string)$response->Item->ListingDetails->StartTime));
            }
            $VariationPicture = isset($response->Item->Variations->Pictures) ? $response->Item->Variations->Pictures : '';
            $variation_picture = [];
            if (!empty($VariationPicture)) {
                $key = (string)$VariationPicture->VariationSpecificName;
                foreach ($VariationPicture->VariationSpecificPictureSet as $Variation) {
                    $variation_picture[$key][(string)$Variation->VariationSpecificValue] = (string)$Variation->PictureURL;
                }
            }
            unset($VariationPicture);
            $list_info['variation_picture'] = json_encode($variation_picture);
            unset($variation_picture);
            $VariationSpecificsSet = isset($response->Item->Variations->VariationSpecificsSet) ? $response->Item->Variations->VariationSpecificsSet : '';
            $variation_specifics = [];
            if (!empty($VariationSpecificsSet)) {

                if (isset($VariationSpecificsSet->NameValueList[0])) {
                    foreach ($VariationSpecificsSet->NameValueList as $nameList) {
                        $key = (string)$nameList->Name;
                        foreach ($nameList->Value as $value) {
                            $variation_specifics[$key][] = (string)$value;
                        }
                    }
                } else {
                    $key = (string)$VariationSpecificsSet->NameValueList->Name;
                    foreach ($VariationSpecificsSet->NameValueList->Value as $value) {
                        $variation_specifics[$key][] = (string)$value;
                    }
                }
            }
            unset($VariationSpecificsSet);
            $list_info['variation_specifics'] = json_encode($variation_specifics);
            unset($variation_specifics);
            $return_policy = [];
            $return_policy['ReturnsAcceptedOption'] = isset($response->Item->ReturnPolicy->ReturnsAcceptedOption) ? (string)$response->Item->ReturnPolicy->ReturnsAcceptedOption : '';
            $return_policy['ReturnsWithinOption'] = isset($response->Item->ReturnPolicy->ReturnsWithinOption) ? (string)$response->Item->ReturnPolicy->ReturnsWithinOption : '';
            $return_policy['RefundOption'] = isset($response->Item->ReturnPolicy->RefundOption) ? (string)$response->Item->ReturnPolicy->RefundOption : '';
            $return_policy['ShippingCostPaidByOption'] = isset($response->Item->ReturnPolicy->ShippingCostPaidByOption) ? (string)$response->Item->ReturnPolicy->ShippingCostPaidByOption : '';
            $return_policy['Description'] = isset($response->Item->ReturnPolicy->Description) ? (string)$response->Item->ReturnPolicy->Description : '';
            $return_policy['ExtendedHolidayReturns'] = isset($response->Item->ReturnPolicy->ExtendedHolidayReturns) ? (string)$response->Item->ReturnPolicy->ExtendedHolidayReturns : '';
            $list_info['return_policy'] = json_encode($return_policy);
            $shipping_details = [];
            if (isset($response->Item->ShippingDetails->ShippingServiceOptions[0])) { //多个国内运输选项
                foreach ($response->Item->ShippingDetails->ShippingServiceOptions as $ShippingServiceOptions) {
                    $key = (int)$ShippingServiceOptions->ShippingServicePriority;
                    $shipping_details['Shipping'][$key]['ShippingService'] = (string)$ShippingServiceOptions->ShippingService;
                    $shipping_details['Shipping'][$key]['ShippingServiceCost'] = (float)$ShippingServiceOptions->ShippingServiceCost;
                    $shipping_details['Shipping'][$key]['ShippingServiceAdditionalCost'] = (float)$ShippingServiceOptions->ShippingServiceAdditionalCost;
                }
            } else {
                $ShippingServiceOptions = $response->Item->ShippingDetails->ShippingServiceOptions;
                $shipping_details['Shipping'][1]['ShippingService'] = (string)$ShippingServiceOptions->ShippingService;
                $shipping_details['Shipping'][1]['ShippingServiceCost'] = (float)$ShippingServiceOptions->ShippingServiceCost;
                $shipping_details['Shipping'][1]['ShippingServiceAdditionalCost'] = (float)$ShippingServiceOptions->ShippingServiceAdditionalCost;
            }
            if (isset($response->Item->ShippingDetails->InternationalShippingServiceOption[0])) { //多个国际运输选项
                foreach ($response->Item->ShippingDetails->InternationalShippingServiceOption as $InternationalShippingServiceOption) {
                    $key = (int)$InternationalShippingServiceOption->ShippingServicePriority;
                    $shipping_details['InternationalShipping'][$key]['ShippingService'] = (string)$InternationalShippingServiceOption->ShippingService;
                    $shipping_details['InternationalShipping'][$key]['ShippingServiceCost'] = (float)$InternationalShippingServiceOption->ShippingServiceCost;
                    $shipping_details['InternationalShipping'][$key]['ShippingServiceAdditionalCost'] = (float)$InternationalShippingServiceOption->ShippingServiceAdditionalCost;
                    $shipToLocation = [];
                    if(isset($InternationalShippingServiceOption->ShipToLocation[0])){
                        foreach($InternationalShippingServiceOption->ShipToLocation as $location){
                            $shipToLocation[]=(string)$location;
                        }
                    }else{
                        $shipToLocation[] = (string)$InternationalShippingServiceOption->ShipToLocation;
                    }


                    $shipping_details['InternationalShipping'][$key]['ShipToLocation'] = $shipToLocation;
                }
            } else {
                $InternationalShippingServiceOption = $response->Item->ShippingDetails->InternationalShippingServiceOption;
                $shipping_details['InternationalShipping'][1]['ShippingService'] = (string)$InternationalShippingServiceOption->ShippingService;
                $shipping_details['InternationalShipping'][1]['ShippingServiceCost'] = (float)$InternationalShippingServiceOption->ShippingServiceCost;
                $shipping_details['InternationalShipping'][1]['ShippingServiceAdditionalCost'] = (float)$InternationalShippingServiceOption->ShippingServiceAdditionalCost;
                $shipping_details['InternationalShipping'][1]['ShipToLocation'] = (string)$InternationalShippingServiceOption->ShipToLocation;



            }
            $shipping_details['ExcludeShipToLocation'] = (array)$response->Item->ShippingDetails->ExcludeShipToLocation;
            $list_info['shipping_details'] = json_encode($shipping_details);
            if(isset($response->Item->OutOfStockControl)&&((string)$response->Item->OutOfStockControl=='true')){
                $list_info['is_out_control'] = 1;
            }
            unset($shipping_details);
            $return['sku_info']=$sku_info;
            $return['list_info']=$list_info;
            return $return;
        } else {
            return false;
        }
    }

    /** 开启无货在线
     * @param $itemId
     * @param $is_out_stock
     * @param int $site
     * @return mixed
     */
    public function changeOutOfStock($itemId,$is_out_stock,$site = 0){
        $xml = '';
        $xml .= '<Item><ItemID>'.$itemId.'</ItemID><OutOfStockControl>'.$is_out_stock.'</OutOfStockControl></Item>';
        $response = $this->buildEbayBody($xml, 'ReviseFixedPriceItem', $site);
        if ($response->Ack == 'Success') {
            $return['status'] = true;
            $return['info'] = 'Success';
        }else{
            $return['status'] = false;
            $return['info'] = isset($response->Errors->LongMessage)?(string)$response->Errors->LongMessage:'未知错误';
        }
        return $return;
    }

    /** 处理天数
     * @param $itemId
     * @param $day
     * @param int $site
     * @return mixed
     */
    public function changeProcessingDays($itemId,$day,$site = 0){
        $xml = '';
        $xml .= '<Item><ItemID>'.$itemId.'</ItemID><DispatchTimeMax>'.$day.'</DispatchTimeMax></Item>';
        $response = $this->buildEbayBody($xml, 'ReviseFixedPriceItem', $site);
        if ($response->Ack == 'Success') {
            $return['status'] = true;
            $return['info'] = 'Success';
        }else{
            $return['status'] = false;
            $return['info'] = isset($response->Errors->LongMessage)?(string)$response->Errors->LongMessage:'未知错误';
        }
        return $return;
    }

    /** 改PAYPAL
     * @param $itemId
     * @param $paypal
     * @param int $site
     * @return mixed
     */
    public function changePayPal($itemId,$paypal,$site = 0){
        $xml = '';
        $xml .= '<Item><ItemID>'.$itemId.'</ItemID><PayPalEmailAddress>'.$paypal.'</PayPalEmailAddress></Item>';
        $response = $this->buildEbayBody($xml, 'ReviseFixedPriceItem', $site);
        if ($response->Ack == 'Success') {
            $return['status'] = true;
            $return['info'] = 'Success';
        }else{
            $return['status'] = false;
            $return['info'] = isset($response->Errors->LongMessage)?(string)$response->Errors->LongMessage:'未知错误';
        }
        return $return;
    }

    /** 修改在线广告的价格
     * @param $itemId
     * @param $sku
     * @param int $is_mul
     * @param int $site
     * @return array
     */
    public function changePrice($itemId,$sku,$is_mul=0,$site=0){
        $return =[];
        $xml='';
        if($is_mul){
            foreach($sku as $key=> $v){
                $xml .= '<InventoryStatus>';
                $xml .= '<ItemID>' . $itemId . '</ItemID>';
                $xml .= '<SKU>'.$key.'</SKU>';
                $xml .= '<StartPrice>'.$v.'</StartPrice>';
                $xml .= '</InventoryStatus>';
            }
        }else{
            foreach($sku as $key=> $v){
                $xml .= '<InventoryStatus>';
                $xml .= '<ItemID>' . $itemId . '</ItemID>';
                $xml .= '<StartPrice>'.$v.'</StartPrice>';
                $xml .= '</InventoryStatus>';
            }
        }
        $response = $this->buildEbayBody($xml, 'ReviseInventoryStatus', $site);
        if ($response->Ack == 'Success'||$response->Ack=='Warning') {
            $return['status'] = true;
            $return['info'] = isset($response->Errors->LongMessage)?'Success'.(string)$response->Errors->LongMessage:'Success';
        }else{
            $return['status'] = false;
            $return['info'] = isset($response->Errors->LongMessage)?(string)$response->Errors->LongMessage:'未知错误';
        }
        return $return;
    }

    /** 修改在线广告的数量 ReviseInventoryStatus
     * @param $itemId
     * @param $sku
     * @param int $is_mul
     * @param int $site
     * @return array
     */
    public function changeQuantity($itemId,$sku,$is_mul=0,$site=0){
        $return =[];
        $xml='';
        if($is_mul){
            foreach($sku as $key=> $v){
                $xml .= '<InventoryStatus>';
                $xml .= '<ItemID>' . $itemId . '</ItemID>';
                $xml .= '<SKU>'.$key.'</SKU>';
                $xml .= '<Quantity>'.$v.'</Quantity>';
                $xml .= '</InventoryStatus>';
            }
        }else{
            foreach($sku as $key=> $v){
                $xml .= '<InventoryStatus>';
                $xml .= '<ItemID>'.$itemId.'</ItemID>';
                $xml .= '<Quantity>'.$v.'</Quantity>';
                $xml .= '</InventoryStatus>';
            }
        }
        $response = $this->buildEbayBody($xml, 'ReviseInventoryStatus', $site);
        if ($response->Ack == 'Success'||$response->Ack=='Warning') {
            $return['status'] = true;
            $return['info'] = isset($response->Errors->LongMessage)?'Success'.(string)$response->Errors->LongMessage:'Success';
        }else{
            $return['status'] = false;
            $return['info'] = isset($response->Errors->LongMessage)?(string)$response->Errors->LongMessage:'未知错误';
        }
        return $return;
    }

    /** 修改在线广告的国内第一运费 和 国际第一运输运费
     * @param $itemId
     * @param $ship_info
     * @param int $site
     * @return array
     */
    public function changeShippingFee($itemId,$ship_info,$site=0){
        $return =[];
        $xml = '';
        $xml .='<Item><ItemID>'.$itemId.'</ItemID><ShippingDetails>';
            foreach($ship_info['Shipping'] as $key=>$shipping){
                $xml .='<ShippingServiceOptions>';
                $xml .='<ShippingService>'.$shipping['ShippingService'].'</ShippingService>';
                $xml .='<ShippingServiceAdditionalCost>'.sprintf("%.2f",$shipping['ShippingServiceAdditionalCost']).'</ShippingServiceAdditionalCost>';
                $xml .='<ShippingServiceCost>'. sprintf("%.2f",$shipping['ShippingServiceCost']).'</ShippingServiceCost>';
                $xml .='<ShippingServicePriority>'.$key.'</ShippingServicePriority>';
                $xml .='</ShippingServiceOptions>';
            }
            foreach($ship_info['InternationalShipping'] as $key=>$shipping){
                $xml .='<InternationalShippingServiceOption>';
                $xml .='<ShippingService>'.$shipping['ShippingService'].'</ShippingService>';
                $xml .='<ShippingServiceAdditionalCost>'.sprintf("%.2f",$shipping['ShippingServiceAdditionalCost']).'</ShippingServiceAdditionalCost>';
                $xml .='<ShippingServiceCost>'.sprintf("%.2f",$shipping['ShippingServiceCost']).'</ShippingServiceCost>';
                $xml .='<ShippingServicePriority>'.$key.'</ShippingServicePriority>';
                foreach($shipping['ShipToLocation'] as $location){
                    $xml .='<ShipToLocation>'.$location.'</ShipToLocation>';
                }
                $xml .='</InternationalShippingServiceOption>';
            }
            if(!empty($ship_info['ExcludeShipToLocation'])){
                foreach($ship_info['ExcludeShipToLocation'] as $exclude)
                $xml .= '<ExcludeShipToLocation>'.$exclude.'</ExcludeShipToLocation>';
            }
        $xml .='</ShippingDetails></Item>';
        $response = $this->buildEbayBody($xml, 'ReviseFixedPriceItem', $site);
        if ($response->Ack == 'Success') {
            $return['status'] = true;
            $return['info'] = 'Success';
        }else{
            $return['status'] = false;
            $return['info'] = isset($response->Errors->LongMessage)?(string)$response->Errors->LongMessage:'未知错误';
        }
        return $return;
    }

    /** 下架
     * @param $itemId
     * @param string $reason
     * @param int $site
     * @return array
     */
    public function endItems($itemId,$reason='NotAvailable Sorry',$site = 0){
        $return =[];
        $xml = '';
        $xml .= '<EndingReason>'.$reason.'</EndingReason>';
        $xml .= '<ItemID>'.$itemId.'</ItemID>';
        $response = $this->buildEbayBody($xml, 'EndItem', $site);
        if ($response->Ack == 'Success') {
            $return['status'] = true;
            $return['info'] = 'Success';
        }else{
            $return['status'] = false;
            $return['info'] = isset($response->Errors->LongMessage)?(string)$response->Errors->LongMessage:'未知错误';
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


    public function getMessages()
    {
        $message_lists = [];
        $order = 0;
        // 1.封装message 的XML DOM
        $before_day = 1;
        $time_begin = date("Y-m-d H:i:s", time() - (86400 * $before_day));
        $time_end = date('Y-m-d H:i:s');
        $arr = explode(' ', $time_end);
        $time_end = $arr[0] . 'T' . $arr[1] . '.000Z';
        $arr = explode(' ', $time_begin);
        $time_begin = $arr[0] . 'T' . $arr[1] . '.000Z';

        $message_xml_dom = '<WarningLevel>High</WarningLevel>
                            <DetailLevel>ReturnSummary</DetailLevel>
                            <StartTime>' . $time_begin . '</StartTime>
                            <EndTime>' . $time_end . '</EndTime>';
        //2.获取消息
        $call = 'GetMyMessages';
        $message_ary = $this->buildEbayBody($message_xml_dom, $call);
        $headers_count = $message_ary->Summary->TotalMessageCount;
        $headers_pages_count = ceil($headers_count / 100); //统计页数

        for ($index = 1; $index <= $headers_pages_count; $index++) {
            $content_xml_dom = '<WarningLevel>High</WarningLevel>
                                <DetailLevel>ReturnHeaders</DetailLevel>
                                <Pagination>
                                    <EntriesPerPage>100</EntriesPerPage>
                                    <PageNumber>' . $index . '</PageNumber>
                                </Pagination>        
                                <StartTime>' . $time_begin . '</StartTime>
                                <EndTime>' . $time_end . '</EndTime>';
            $content = $this->buildEbayBody($content_xml_dom, 'GetMyMessages');
            if (isset($content->Messages->Message)) {
                foreach ($content->Messages->Message as $message) {
                    /*
                     *             message 数据格式 样例
                                    SimpleXMLElement Object
                                    (
                                        [Sender] => priya.suryavanshi
                                        [SendingUserID] => 774805616
                                        [RecipientUserID] => wintrade9
                                        [SendToName] => wintrade9
                                        [Subject] => 關於： priya.suryavanshi 針對物品編號 222123713737 提出問題，結束時間為 2016-08-18 16:16:14–NEW 25M Elastic Cord Rope String Bead Bracelet DIY Stretch Beading Thread Rope
                                        [MessageID] => 80473418726
                                        [ExternalMessageID] => 1340213839016
                                        [Flagged] => false
                                        [Read] => false
                                        [ReceiveDate] => 2016-08-04T09:16:42.000Z
                                        [ExpirationDate] => 2017-08-04T09:16:42.000Z
                                        [ItemID] => 222123713737
                                        [ResponseDetails] => SimpleXMLElement Object
                                        (
                                             [ResponseEnabled] => true
                                             [ResponseURL] => http://contact.ebay.com.hk/ws/eBayISAPI.dll?M2MContact&item=222123713737&requested=priya.suryavanshi&qid=1340213839016&redirect=0&messageid=m80473418726
                                        )

                                        [Folder] => SimpleXMLElement Object
                                        (
                                            [FolderID] => 0
                                         )

                                        [MessageType] => ResponseToASQQuestion
                                        [Replied] => false
                                        [ItemEndTime] => 2016-08-18T08:16:14.000Z
                                        [ItemTitle] => NEW 25M Elastic Cord Rope String Bead Bracelet DIY Stretch Beading Thread Rope
                                    )*/

                    $message_lists[$order]['message_id'] = $message->MessageID;
                    $message_lists[$order]['from_name'] = $message->Sender;
                    $message_lists[$order]['from'] = $message->SendingUserID;
                    $message_lists[$order]['to'] = $message->SendToName;
                    $message_lists[$order]['labels'] = '';
                    $message_lists[$order]['label'] = 'INBOX';
                    $message_lists[$order]['date'] = $message->ReceiveDate;
                    $message_lists[$order]['subject'] = $message->Subject;
                    $message_lists[$order]['attachment'] = ''; //附件
                    $message_lists[$order]['content'] = base64_encode(serialize(['ebay' => (string)$message->Subject]));
                    $message_fields_ary = [
                        'ItemID' => (string)$message->ItemID,
                        'ExternalMessageID' => (string)$message->ExternalMessageID,
                    ];
                    $message_lists[$order]['channel_message_fields'] = base64_encode(serialize($message_fields_ary));
                    $order += 1;
                }
            }

        }

        return (!empty($message_lists)) ? $message_lists : false;

    }

    public function sendMessages($replyMessage)
    {
        $message_obj = $replyMessage->message; //关联关系  获取用户邮件

        if (!empty($message_obj)) {
            $fields = unserialize(base64_decode($message_obj->channel_message_fields)); //渠道特殊值
            //1.封装XML DOM
            $reply_xml_dom = '<RequesterCredentials>
                              <eBayAuthToken>' . $this->requestToken . '</eBayAuthToken>
                              </RequesterCredentials>
                              <WarningLevel>High</WarningLevel>
                              <ItemID>' . $fields['ItemID'] . '</ItemID>
                              <MemberMessage>
                              <Body>' . $replyMessage->content . '</Body>
                              <DisplayToPublic>false</DisplayToPublic>
                              <EmailCopyToSender>true</EmailCopyToSender>
                              <ParentMessageID>' . $fields['ExternalMessageID'] . '</ParentMessageID>
                              <RecipientID>' . $message_obj->from_name . '</RecipientID>
                              </MemberMessage>';

            $content = $this->buildEbayBody($reply_xml_dom, 'AddMemberMessageRTQ');

            return $content->Ack == 'Success' ? true : false;
        }
    }


}