<?php
/**
 * Created by PhpStorm.
 * User: lilifeng
 * Date: 2016-05-26
 * Time: 14:23
 */

namespace App\Modules\Channel\Adapter;
header("Content-type:text/html;charset=utf-8");
use App\Models\Order\ItemModel;
use Illuminate\Support\Facades\DB;
set_time_limit(1800);

Class WishAdapter implements AdapterInterface
{

    private $publish_code;
    private $client_id;
    private $client_secret;
    private $redirect_uri;
    private $refresh_token;
    private $access_token;
    private $expiry_time;
    private $proxy_address;


    public function __construct($config)
    {
        $this->publish_code = $config["publish_code"];
        $this->client_id = $config["client_id"];
        $this->client_secret = $config["client_secret"];
        $this->redirect_uri = $config["redirect_uri"];
        $this->refresh_token = $config["refresh_token"];
        $this->expiry_time = $config['expiry_time'];
        $this->proxy_address = $config['proxy_address'];
        $this->wish_sku_resolve = $config['sku_resolve'];
        $access_token = $this->isResetAccesstoken();   //是否过期的accesstoken
        $this->access_token = $access_token == false ? $config["access_token"] : $access_token;

    }


    public function getOrder($orderID)
    {
        return $orderID;
    }

    public function listOrders($startDate, $endDate, $status = [], $perPage = 10)
    {
        $hasOrder = true;
        $start = 0;
        $orders = [];
        $returnOrders=[];

        while ($hasOrder) {
            $url = "https://china-merchant.wish.com/api/v2/order/get-fulfill?";
            $apiArr = array();//api请求数组
            $apiArr['limit'] = urlencode($perPage);
            $apiArr['start'] = urlencode($start * $perPage);
            if ($startDate != '') {
                $apiArr['since'] = urlencode(date("Y-m-d", strtotime($startDate)));
            }
            $apiArr['access_token'] = urldecode($this->access_token);
            $apiString = http_build_query($apiArr);
            $url = $url . $apiString;
            $orderjson = $this->getCurlData($url);
            $orderList = json_decode($orderjson, true);
            if (isset($orderList['code']) && ($orderList['code'] == 0) && !empty($orderList['data'])) {
                $start++;
                foreach ($orderList['data'] as $order) {
                    $orders[$order['Order']['transaction_id']][] = $order;
                }

            } else {
                var_dump($orderList);
                $hasOrder = false;
            }
        }

        foreach ($orders as $key=> $order) {
           $midOrder =    $this->parseOrder($order,$key);
            if($midOrder){
                $returnOrders[] = $midOrder;
            }
        }



        return $returnOrders;
    }


    public function returnTrack()
    {
        return 'returnTrack';
    }

    public function parseOrder($order,$transaction_number)
    {

        $orderInfo = array();
        $amount = 0; //先设置 总金额为0
        $amount_shipping = 0; //先设置运费为0
        $channel_ordernum = array(); //渠道号数组
        $items = array();//SKU 信息


        foreach ($order as $key => $orderSingle) {
            $orderSingle = $orderSingle['Order'];


            //ItemModel
            $thisOrder = ItemModel::where('channel_order_id', $orderSingle['order_id'])->first();     //判断一下这订单 是否已经插入过
            if ($thisOrder) {

                echo  $orderSingle['order_id'].' 存在<br/>';
                continue;
            }

            //先判断这个order_id 是否存在
            $orderInfo["email"] = ''; //wish API 不返回
            $orderInfo['currency'] = 'USD';
            $orderInfo['payment'] = 'moonarstore@gmail.com';
            $orderInfo['shipping'] = isset($orderSingle['shipping_provider']) ? $orderSingle['shipping_provider'] : '';
            $orderInfo['shipping_firstname'] = isset($orderSingle['ShippingDetail']['name'])?$orderSingle['ShippingDetail']['name']:''; //只有一个名字字段
            $orderInfo['shipping_lastname'] = '';
            $orderInfo['shipping_address'] = isset($orderSingle['ShippingDetail']['street_address1'])?$orderSingle['ShippingDetail']['street_address1']:'';
            $orderInfo['shipping_address1'] = '';
            $orderInfo['shipping_city'] = isset($orderSingle['ShippingDetail']['city'])?$orderSingle['ShippingDetail']['city']:'';
            $orderInfo['shipping_state'] = isset($orderSingle['ShippingDetail']['state'])?$orderSingle['ShippingDetail']['state']:'';
            $orderInfo['shipping_country'] = isset($orderSingle['ShippingDetail']['country'])?$orderSingle['ShippingDetail']['country']:'';
            $orderInfo['shipping_zipcode'] = isset($orderSingle['ShippingDetail']['zipcode'])?$orderSingle['ShippingDetail']['zipcode']:'';
            $orderInfo['shipping_phone'] = isset($orderSingle['ShippingDetail']['phone_number'])?$orderSingle['ShippingDetail']['phone_number']:'';
            $orderInfo['payment_date'] = $this->getPayTime($orderSingle['order_time']);

            //处理一下 SKU的前后缀问题
            $erpSku =    $this->filter_sku($orderSingle['sku'],$this->wish_sku_resolve); //根据账号的sku解析设定
            $allSkuNum = $erpSku['skuNum'];
            unset($erpSku['skuNum']);
            foreach($erpSku as $sku){
                $skuArray = [];
                $skuArray['channel_sku'] = $orderSingle['sku'];
                $skuArray['sku'] = $sku['erpSku'];
                $skuArray['currency'] = 'USD';
                $skuArray['price'] = $orderSingle['price']/$allSkuNum;
                $skuArray['quantity'] = $orderSingle['quantity']*$sku['qty'];
                $skuArray['orders_item_number'] = $orderSingle['product_id'];
                $skuArray['channel_order_id'] = $orderSingle['order_id'];
                $items[] = $skuArray;
            }
            $channel_ordernum[] = $orderSingle['order_id'];
            $amount = $amount + (int)$orderSingle['quantity'] * (float)$orderSingle['price'];
            $amount_shipping = $amount_shipping + (int)$orderSingle['quantity'] * (float)$orderSingle['shipping'];

        }
        if(!empty($items)){
            $orderInfo['amount'] = $amount+$amount_shipping; //WISH的总金额分两部分  要把运费加上去
            $orderInfo['amount_shipping'] = $amount_shipping;
            $orderInfo['channel_ordernum'] = join('+', $channel_ordernum);
            $orderInfo['items'] = $items;
            $orderInfo['transaction_number'] = $transaction_number;
        }else{
            return false;
        }


        return $orderInfo;

    }

    //wish sku 解析规则
    // 1 处理捆绑的情况   A+B
    // 2 去除前后缀    $type = 2 的时候 sku前缀是  S*001KU[TEST]  这样存在的
    // 3 处理SKU（10）  处理打包的情况
    public function filter_sku($channel_sku,$type=1){

        $tmpSku = explode('+', $channel_sku);
        $skuNum=0;
        $returnSku =array();
        foreach ($tmpSku as $k => $sku){

            if (stripos($sku, '[') !== false) {
                $sku = preg_replace('/\[.*\]/', '', $sku);
            }
            if($type==2){

                $prePart = substr($sku,0,1);
                $suffPart = substr($sku,4);
                $sku = $prePart.$suffPart;
                $newSku = $sku;
            }else{

                $tmpErpSku = explode('*', $sku);
                $i = count($tmpErpSku)-1;
                $newSku = $tmpErpSku[$i];
            }


            $qty = 1;
            if (strpos($newSku, '(') !== false) {
                $matches = array();
                preg_match_all("/(.*?)\([a-z]?([0-9]*)\)?/i", $newSku, $matches);
                $newSku = trim($matches[1][0]);
                $qty = trim($matches[2][0]) ? trim($matches[2][0]) : 1;
            }
            $skuArray =array();
            $skuArray['erpSku']=$newSku;
            $skuArray['qty'] = $qty;

            $skuNum = $skuNum+$qty;
            $returnSku[]=$skuArray;
        }


        $returnSku['skuNum'] = $skuNum;

       return $returnSku;

    }

    public function getPayTime($time)
    {
        return date('Y-m-d H:i:s', strtotime($time) + 8 * 3600);
    }



    public function isResetAccesstoken()
    {
        $now = date("Y-m-d H:i:s");
        $hours = (strtotime($now) - strtotime($this->expiry_time)) / 60 / 60;

        if ($hours >10) {
            $json = $this->getAccessTokenByRefresh(); //获取最新的access_token
            $data = json_decode($json, true);

            if($data['code']==0 && !empty($data['data'])){
                DB::table('channel_accounts')->where('wish_client_id', $this->client_id)->update([
                    'wish_access_token' => $data['data']["access_token"],
                    'wish_refresh_token' =>$data['data']['refresh_token'],
                    'wish_expiry_time' => date('Y-m-d H:i:s',$data['data']['expiry_time'])
                ]);
            }else{
                return false;
            }
            return $data['data']["access_token"];
        } else {
            return false;
        }
    }

    /**
     * 用refresh重新获取访问token
     * 并把wish的token重新更新
     */
    public function getAccessTokenByRefresh(){
        $getData = array();
        $getData['client_id'] 		= $this->client_id;
        $getData['client_secret'] 	= $this->client_secret;
        $getData['refresh_token'] 	= $this->refresh_token;
        $getData['grant_type'] 		= 'refresh_token';
        $apiString = http_build_query($getData);
        $url = 'https://merchant.wish.com/api/v2/oauth/refresh_token?';
        $url = $url . $apiString;
        $result = $this->getCurlData($url);
        return $result;
    }

    /**
     * Curl http Get 数据
     * 使用方法：
     * getCurlData
     */
    public function getCurlData($url, $time = '120')
    {

        $curl = curl_init(); // 启动一个CURL会话

        curl_setopt($curl, CURLOPT_URL, $url); // 要访问的地址
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0); // 对认证证书来源的检查
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 2); // 从证书中检查SSL加密算法是否存在
        curl_setopt($curl, CURLOPT_USERAGENT, !empty($_SERVER ['HTTP_USER_AGENT']) ? $_SERVER ['HTTP_USER_AGENT'] : ''); // 模拟用户使用的浏览器
        curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1); // 使用自动跳转
        curl_setopt($curl, CURLOPT_AUTOREFERER, 1); // 自动设置Referer

        if ($this->proxy_address != '') {
            curl_setopt($curl, CURLOPT_PROXY, $this->proxy_address);
            curl_setopt($curl, CURLOPT_PROXYPORT, '808');
        }
        curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, $time);
        curl_setopt($curl, CURLOPT_TIMEOUT, $time); // 设置超时限制防止死循环
        curl_setopt($curl, CURLOPT_HEADER, 0); // 显示返回的Header区域内容
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1); // 获取的信息以文件流的形式返回

        $tmpInfo = curl_exec($curl);
        if (curl_errno($curl)) {
            $error = curl_error($curl); //异常错误
            echo $error . '<br/>';
        }
        curl_close($curl); // 关闭CURL会话


        return $tmpInfo; // 返回数据
    }
}