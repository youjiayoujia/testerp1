<?php
namespace App\Modules\Logistics\Adapter;

use Channel;
use App\Models\Channel\AccountModel;
use App\Models\PackageModel;
use App\Models\OrderModel;

class SmtAdapter extends BasicAdapter
{
    private $_senderAddress = array( //线上发货的发货地址
        '3' => array(
            'sender' => array( //深圳仓发货地址,必须是英文
                'country' => 'CN',
                //国家简称
                'province' => 'GUANGDONG',
                //省/州,（必填，长度限制1-48字节）
                'city' => 'SHENZHEN',
                //城市
                'streetAddress' => '2nd Floor, Buliding 6,No.146 Pine Road , Mengli Garden Industrial, Longhua District',
                //街道 ,（必填，长度限制1-90字节）
                //'fax'           => '',
                'phone' => '430626198609190952',
                //phone（长度限制1- 54字节）,phone,mobile两者二选一
                //'mobile'        => '', //mobile（长度限制1-30字节）
                'name' => 'Bolin Mao',
                //姓名,（必填，长度限制1-90字节）
                'postcode' => '518109'
                //邮编
            ),
            'pickup' => array( //深圳仓揽货地址，写中文
                'country' => 'CN', //国家简称
                'province' => '广东省', //省/州,（必填，长度限制1-48字节）
                'city' => '深圳市', //城市
                'county' => '龙华新区', //区
                'streetAddress' => '深圳市龙华新区油松路146号梦丽园工业园6栋2楼', //街道 ,（必填，长度限制1-90字节）
                //'fax'           => '',
                'phone' => '18565631099', //phone（长度限制1- 54字节）,phone,mobile两者二选一
                //'mobile'        => '', //mobile（长度限制1-30字节）
                'name' => '毛波林', //姓名,（必填，长度限制1-90字节）
                'postcode' => '518109'  //邮编
            )
        ),
        '4' => array(
            'sender' => array( //义乌金华仓发货地址,必须是英文
                'country' => 'CN',
                //国家简称
                'province' => 'ZHEJIANG',
                //省/州,（必填，长度限制1-48字节）
                'city' => 'JINHUA',
                //城市
                'streetAddress' => 'Buliding 1-2, Jinyi Postal Park, No.2011, JinGangDaDao West, JINDONG',
                //街道 ,（必填，长度限制1-90字节）
                //'fax'           => '',
                'phone' => '13715115766',
                //phone（长度限制1- 54字节）,phone,mobile两者二选一
                //'mobile'        => '', //mobile（长度限制1-30字节）
                'name' => 'liubaojun',
                //姓名,（必填，长度限制1-90字节）
                'postcode' => '321000'
                //邮编
            ),
            'pickup' => array( //义务金华仓揽货地址，写中文
                'country' => 'CN', //国家简称
                'province' => '浙江省', //省/州,（必填，长度限制1-48字节）
                'city' => '金华市', //城市
                'county' => '金东区', //区，必填
                'streetAddress' => '金东傅村镇金义都市新区金港大道 2011号（金义邮政电子商务示范园）1号二楼', //街道 ,（必填，长度限制1-90字节）
                //'fax'           => '',
                'phone' => '13715115766', //phone（长度限制1- 54字节）,phone,mobile两者二选一
                //'mobile'        => '', //mobile（长度限制1-30字节）
                'name' => '刘保军', //姓名,（必填，长度限制1-90字节）
                'postcode' => '321000'  //邮编
            )
        )
    );

    public function __construct($config)
    {
    }


    /**
     * 获取物流跟踪号
     * @see \App\Modules\Logistics\Adapter\BasicAdapter::getTracking()
     */
    public function getTracking($package)
    {
        $flag = false;
        $orderId = $package->order->channel_ordernum; //内单号
        $warehouseId = $package->warehouse_id; //仓库
        $shipId = $package->logistics_id; //物流
        $channel_account_id = $package->channel_account_id;
        list($name, $channel) = explode(',', $package->logistics->type);
        $warehouseCarrierService = $channel;    //物流方式
        if(!$package->logistics_order_number){           
            //获取渠道帐号资料
            $account = AccountModel::findOrFail($channel_account_id);
            $smtApi = Channel::driver($account->channel->driver, $account->api_config);
            
            $qd_result = $smtApi->getOnlineLogisticsServiceListByOrderId($orderId);
            if (array_key_exists('success', $qd_result) && $qd_result['success']) {
                foreach ($qd_result['result'] as $address) {
                    if ($address['logisticsServiceId'] == $channel) {
                        $flag = true;
                    }
                }
            }
            
            if(false === $flag){
                return array('code' => 'error','result' => 'The order does not support this channel');
            }
              
            $totalWeight = 0;
            $productNum = 0;
            $productData = array();
            foreach ($package->items as $packageItem) {
                $productNum += $packageItem->quantity;
            }
            $productData = array(
                'categoryCnDesc'       => $package->items ? $package->items->first()->item->product->declared_cn : '连衣裙',
                'categoryEnDesc'       => $package->items ? $package->items->first()->item->product->declared_en : 'dress',
                'productDeclareAmount' => $package->items->first()->item->declared_value,
                'productId'            => $package->order ? ($package->order->items ? $package->order->items->first()->orders_item_number : 0) : 0,
                'productNum'           => $productNum,
                'productWeight'        => $package->total_weight,
                'isContainsBattery'    => $package->is_battery ? 1 : 0,
                /*'isAneroidMarkup'      => 0,
                 'isOnlyBattery'        => 0,*/
            );
            
            $addressArray = array(
                'receiver' => array( //收件人地址
                    'country' => $package->shipping_country,
                    //国家简称, 速卖通下单下来应该就是吧
                    'province' => $package->shipping_state,
                    //省/州,（必填，长度限制1-48字节）
                    'city' => $package->shipping_city,
                    //城市
                    'streetAddress' => trim($package->shipping_address . ' ' . $package->shipping_address1),
                    //街道 ,（必填，长度限制1-90字节）
                    'phone' => $package->shipping_phone,
                    //phone（长度限制1- 54字节）,phone,mobile两者二选一
                    'name' => trim($package->shipping_firstname . ' ' . $package->shipping_lastname),
                    //姓名,（必填，长度限制1-90字节）
                    'postcode' => $package->shipping_zipcode
                    //邮编
                ),
            );
            
            $data = array();
            $data['tradeOrderId'] = $orderId;
            $data['tradeOrderFrom'] = 'SOURCING';
            $data['warehouseCarrierService'] = $warehouseCarrierService;
            $data['domesticLogisticsCompanyId'] = '-1'; //国内快递ID;(物流公司是other时,ID为-1)
            $data['domesticLogisticsCompany'] = '上门揽收'; //国内快递公司名称;(物流公司Id为-1时,必填)
            $data['domesticTrackingNo'] = 'None'; //国内快递运单号,长度1-32
            
            //获取SMT平台线上发货地址
            $address_api = "alibaba.ae.api.getLogisticsSellerAddresses";
            $address_smt = array(
                'request' => '["sender","pickup"]'
            );
            $address_result = $smtApi->getJsonDataUsePostMethod($address_api, $address_smt);
            $address_result = json_decode($address_result, true);
            $addressArray = array_merge($addressArray, $this->_senderAddress[$package->warehouse_id]);
            $addressArray['sender']['addressId'] = $address_result['senderSellerAddressesList'][0]['addressId'];
            $addressArray['pickup']['addressId'] = $address_result['pickupSellerAddressesList'][0]['addressId'];
            
            $data['declareProductDTOs']         = json_encode([$productData]);  //二维数组
            $data['addressDTOs']                = json_encode($addressArray);
             
            $api = 'api.createWarehouseOrder';
            $rs = $smtApi->getJsonDataUsePostMethod($api,$data);
            $result = json_decode($rs,true);
            echo "<pre>";
            print_r($result);
            if(array_key_exists('success', $result) && $result['result']['success']){
                if (array_key_exists('intlTracking', $result['result'])) { //有挂号码就要返回，不然还得再调用API获取
                    $data['channel_listnum'] = $result['result']['intlTracking'];
                    $data['warehouseOrderId'] = $result['result']['warehouseOrderId'];
                    return array('code' => 'success', 'result' => $result['result']['intlTracking'] );
                }else{
                    return array('code' => 'again', 'result' => '', 'result_other' => $result['result']['warehouseOrderId']);
                }
            } else {
                return array('code' => 'error', 'result' => 'error description.');
            }
        }else{
            $res = $this->getOnlineLogisticsInfo($channel_account_id,$orderId);
            $onlineLogisticsId = $package->logistics_order_number;
            if(array_key_exists('success', $res) && $res['success']){
                if(!empty($res['result'])){
                    foreach ($res['result'] as $row){
                        //分仓，估计要结合 物流分类+状态 来进行判断获取国际运单号
                        if ($row['internationalLogisticsType'] == $warehouseCarrierService && $row['onlineLogisticsId'] == $onlineLogisticsId) { //渠道和物流内单号对应上了
                            return array('code' => 'success', 'result' => $row['internationallogisticsId'],'result_other' => $onlineLogisticsId);//国际运单号;
                        }
                    }
                }else{
                    return array('code' => 'again', 'result' => '', 'result_other' => $onlineLogisticsId);//国际运单号;
                }
            }
            
        }
        
    }

    /**
     * 获取线上发货物流订单信息
     * @param int $channel_account_id 渠道帐号id
     */
    public function getOnlineLogisticsInfo($channel_account_id, $orderId)
    {
        $account = AccountModel::findOrFail($channel_account_id);
        $smtApi = Channel::driver($account->channel->driver, $account->api_config);
        $action = 'api.getOnlineLogisticsInfo';
        $parameter = 'orderId=' . $orderId . '&logisticsStatus=wait_warehouse_receive_goods';
        $result = $smtApi->getJsonData($action, $parameter);
        return json_decode($result, true);
    }

    /**
     * 创建线上发货物流订单
     * @param unknown $package
     */
    /*
    public function createWarehouseOrder($package){
        $orderId     = $package->order->channel_ordernum; //内单号
        $warehouseId = $package->warehouse_id; //仓库
        $shipId = $package->logistics_id; //物流
        $channel_account_id = $package->channel_account_id;
        
        list($name, $channel) = explode(',',$package->logistics->type);
        //$warehouseCarrierService = $this->getWarehouseCarrierService($channel,$warehouseId);
        $warehouseCarrierService = $channel;  //物流方式
        $totalWeight = 0;
        $productData = array();
        foreach($package->items as $key => $item){
            $totalWeight += $item->item->weight;
            $products_declared_cn = $item->item->product->declared_cn;
            $products_declared_en = $item->item->product->declared_en;
            $products_declared_value = $item->item->product->declared_value;
            $productId = $item->item->product->id;
            $productNum =  $item->quantity;
        }
        
        $productData = array(
            'categoryCnDesc'       => $package->decleared_cname,
            'categoryEnDesc'       => $package->decleared_ename,
            'productDeclareAmount' => $package->decleared_value,
            'productId'            => $productId,
            'productNum'           => $productNum,
            'productWeight'        => $totalWeight,
            'isContainsBattery'    => 0
        );
        
        $addressArray = array(
            'receiver' => array( //收件人地址
                'country'       => $package->shipping_country, //国家简称, 速卖通下单下来应该就是吧
                'province'      => $package->shipping_state, //省/州,（必填，长度限制1-48字节）
                'city'          => $package->shipping_city, //城市
                'streetAddress' => $package->shipping_address . ' ' . $package->shipping_address1, //街道 ,（必填，长度限制1-90字节）
                'phone'         => $package->shipping_phone, //phone（长度限制1- 54字节）,phone,mobile两者二选一
                'name'          => $package->shipping_firstname . " " . $package->shipping_lastname, //姓名,（必填，长度限制1-90字节）
                'postcode'      => $package->shipping_zipcode  //邮编
            ),
        );        
        
        $data = array();
        $data['tradeOrderId'] = $orderId;
        $data['tradeOrderFrom'] = 'SOURCING';
        $data['warehouseCarrierService'] = $warehouseCarrierService;
        $data['domesticLogisticsCompanyId'] = '-1'; //国内快递ID;(物流公司是other时,ID为-1)
        $data['domesticLogisticsCompany']   = '上门揽收'; //国内快递公司名称;(物流公司Id为-1时,必填)
        $data['domesticTrackingNo']         = 'None'; //国内快递运单号,长度1-32
        
        //获取SMT平台线上发货地址
        $address_api = "alibaba.ae.api.getLogisticsSellerAddresses";
        $address_smt = array(
            'request'=>'["sender","pickup"]'
        );
        $address_result = parent::getJsonDataUsePostMethod($address_api, $address_smt);
        $address_result = json_decode($address_result, true);
        
        $addressArray = array_merge($addressArray, $this->_senderAddress['5']);
        $addressArray['sender']['addressId'] = $address_result['senderSellerAddressesList'][0]['addressId'];
        $addressArray['pickup']['addressId'] = $address_result['pickupSellerAddressesList'][0]['addressId'];
        $data['declareProductDTOs']         = json_encode($productData);
        $data['addressDTOs']                = json_encode($addressArray);
        
       $api = 'api.createWarehouseOrder';
       //获取渠道帐号资料
       $account = AccountModel::findOrFail($channel_account_id);
       $smtApi = Channel::driver($account->channel->driver, $account->api_config);
       $result = json_decode($smtApi->getJsonDataUsePostMethod($api,$data));

       if(array_key_exists('success', $result)){
           if ($result['result']['success']){
               if (array_key_exists('intlTracking', $result['result'])) { //有挂号码就要返回，不然还得再调用API获取
                   $data['channel_listnum'] = $result['result']['intlTracking'];
                   $data['warehouseOrderId'] = $result['result']['warehouseOrderId'];
                   //OrderModel::where('id',$package->order->id)->update($data);
                   return array('code' => 'success', 'result' => $result['result']['intlTracking'] );                                     
               }               
           }
       }else{
           return array('code' => 'error','result' => 'error description.');                  
       }
        
    }*/

    /**
     * 根据渠道代码、货仓id获取线上发货物流方案
     * @param string $channelName
     * @param int $warehouse_id
     * @return string
     */
    public function getWarehouseCarrierService($channelName, $warehouse_id)
    {
        $defineLogisticsService = array('中国邮政挂号小包' => 'CPAM', '中国邮政平常小包+' => 'YANWEN_JYT');   //渠道代码
        $defineDetailShipService = array(
            'YANWEN_JYT' => array(                  //中国邮政平常小包+ 对应的物流ID
                '5' => 'YANWENJYT_WLB_CPAMSZ',   //深圳
                '2' => 'YANWENJYT_WLB_CPAMJH'    //先设置成义乌的，后期开通了再设置成金华的//'YANWENJYT_WLB_CPAMJH' //金华，目前未开通
            ),
            'CPAM' => array(                        //中国邮政挂号小包
                '5' => 'CPAM_WLB_CPAMSZ',        //深圳
                '2' => 'CPAM_WLB_CPAMJH'         //金华
            )
        );

        $logisticsCode = $defineLogisticsService[$channelName];

        $warehouseCarrierService = $defineDetailShipService[$logisticsCode][$warehouse_id]; //实际发货物流服务key
        return $warehouseCarrierService;

    }

}

?>