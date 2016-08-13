<?php
/** 线上Eub
 * Created by PhpStorm.
 * User: lilifeng
 * Date: 2016-08-10
 * Time: 13:25
 */

namespace App\Modules\Logistics\Adapter;

class EubAdapter extends BasicAdapter
{


    public function __construct($config)
    {


        //var_dump($config);
    }


    public function getTracking($package)
    {
        $response = $this->doUpload($package);



        return 1;
    }



    public function doUpload($package){



        $shipToAddress = array( //收件人地址信息
            'Contact' 	  => $package->shipping_firstname.' '.$package->shipping_lastname,
            'Street' 	  => $package->shipping_address . ' ' .$package->shipping_address1,
            'City' 		  => $package->shipping_city,
            'Province'    => $package->shipping_state,
            'CountryCode' => $package->shipping_country,
            'Postcode' 	  => $package->shipping_zipcode,
            'Phone' 	  => $package->shipping_phone,
            'Email' 	  => !empty($package->email)?$package->email:'report@moonarstore.com'
        );

        $tArray = array();
        foreach ($package->items as $key => $item) {
            $tArray[$item->orderItem->channel_sku][] = $item;
        }




        $new_data = array();
        $i = 0;
        foreach($tArray as $key => $value){

            $all_count = 0;
            $all_weight = 0.01;
            $all_value = 0.01;
            $all_sku ='';
            $title_en ='';
            $title_cn ='';

            foreach($value as $v){
                $all_count += $v->quantity;
                $all_sku[] =$v->orderItem->sku;
                $all_value += $v->item->product->declared_value*$v->quantity;
                $all_weight += $v->item->product->weight*$v->quantity;
                $title_en = $v->item->product->declared_en;
                $title_cn = $v->item->product->declared_cn;
            }

            //$new_data[$i]['item'] = $value[0];
            $new_data[$i]['sku'] = implode('*',$all_sku);
            $new_data[$i]['value'] =$all_value>15?15:$all_value;
            $new_data[$i]['weight'] = $all_weight;
            $new_data[$i]['title_en'] = $title_en;
            $new_data[$i]['title_cn'] = $title_cn;
            $new_data[$i]['from'] = 'China';
            $new_data[$i]['fromCode'] = 'CN';
            $i++;

        }




        var_dump($new_data);
        exit;

        $tmp = array();
        for ($i = 0; $i < count($res); $i++){
            if ($res[$i]['orders_item_number'] == 0 && $res[$i]['transactionID'] == 0){
                $tmp[] = $res[$i]['orders_sku'].'*'.$res[$i]['item_count'];
                unset($tArray[$i]);
            }else {
                for ($j = $i+1; $j < count($res); $j++){
                    if ($res[$i]['orders_item_number'] == $res[$j]['orders_item_number'] && $res[$i]['transactionID'] == $res[$j]['transactionID']){
                        $tmp[] = $res[$j]['orders_sku'].'*'.$res[$j]['item_count'];
                        unset($tArray[$j]);
                    }
                }
            }
        }
        array_unique($tmp);
        if ($tmp){
            $tArray[0]['orders_sku'] = $tArray[0]['orders_sku'].'*'.$tArray[0]['item_count'].' '.implode(' ', $tmp);
            $tArray[0]['item_count'] = 1;
        }



        $ItemArray = array();
        foreach ($tArray as $ts) {
            if (stripos($ts['orders_sku'], '*') > 0){//是上边组合后的SKU
                $tempArr = explode(' ', $ts['orders_sku']);
                $value = 0;
                $weight = 0;
                foreach ($tempArr as $k => $v){
                    list($sku, $count) = explode('*', $v);
                    $skuInfo =  $this->getProductsCustomInfo($sku, '');

                    $value += $skuInfo['value'] * $count;
                    $weight += $skuInfo['weight'] * $count;
                    if ($k == 0){
                        $title_cn = $skuInfo['title_cn'];
                        $title_en = $skuInfo['title_en'];
                        $fromCode = $skuInfo['fromCode'];
                        $from = $skuInfo['from'];
                    }
                }
                $skuArray = array('sku' => $ts['orders_sku'], 'value' => $value, 'weight' => $weight, 'title_cn' => $title_cn, 'title_en' => $title_en, 'fromCode' => $fromCode, 'from' => $from);

            }else {
                $skuArray = $this->getProductsCustomInfo($ts['orders_sku'], $ts['item_title']);
            }
            $transactionID = ($ts['transactionID'] == NULL || $ts['transactionID'] == '') ? '0' : $ts['transactionID'];
            $ItemArray[] = array(
                'EBayItemID' 			=> $ts['orders_item_number'],					//ebay物品号
                'EBayTransactionID' 	=> $transactionID,								//ebay交易号，拍卖的物品请输入0
                'EBayBuyerID' 			=> $rs['buyer_id'],								//ebay买家ID
                'EBayItemTitle' 		=> $ts['orders_item'],							//ebay商品标题
                'EBayEmail' 			=> $rs['buyer_email'],							//买家ebay邮箱
                'SoldQTY'			 	=> $ts['item_count'],							//卖出数量
                'PostedQTY' 			=> $ts['item_count'],							//寄货数量，不能为0
                'SalesRecordNumber' 	=> $ts['erp_orders_id'], 						//用户从ebay上下载时ebay的销售编号
                'OrderSalesRecordNumber'=> $ts['erp_orders_id'],						//订单销售编号，如果在ebay上合并订单，会产生一个新的SalesRecordNumber
                'OrderID' 				=> $ts['orderlineitemid'], 						//ebay合并订单时生成的一个新的Order ID
                'EBaySiteID' 			=> 0, 											//站点ID
                'ReceivedAmount' 		=> 15, 											//实际收到金额
                'PaymentDate' 			=> $this->splitTime($rs['orders_paid_time']) ,  //买家付款日期
                'SoldPrice' 			=> 15, 											//卖出价格
                'SoldDate' 				=> $this->splitTime($rs['orders_paid_time']) ,  //卖出日期
                'CurrencyCode' 			=> $rs['currency_type'], 						//货币符号
                'EBayMessage' 			=> '', 											//买家 eBay 留言
                'PayPalEmail' 			=> '', 											//买家 PayPal 电邮地址
                'PayPalMessage' 		=> '', 											//买家 PayPal 留言
                'Note' 					=> '', 											//附注
                'SKU' 					=> array( 										//产品报关信息
                    'SKUID' 			=> $skuArray['sku'],
                    'DeclaredValue' 	=> $skuArray['value'],  //物品申报价值
                    'Weight' 			=> $skuArray['weight'], //物品重量
                    'CustomsTitle' 		=> $skuArray['title_cn'].'('.$skuArray['sku'].'*'.$ts['item_count'].') '.$this->getAdapterSpec($oID, $skuArray['sku']).' '.date('Y/m/d'), //中文报关名称+转接头接口
                    'CustomsTitleEN' 	=> $skuArray['title_en'],//英文报关名称
                    'OriginCountryCode' => $skuArray['fromCode'],//原产地国家代码
                    'OriginCountryName' => $skuArray['from'] //原产地
                )
            );
        }


















        return 123;
    }
}