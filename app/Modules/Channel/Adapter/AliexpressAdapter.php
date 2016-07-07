<?php
/**
 * Created by PhpStorm.
 * User: lilifeng
 * Date: 2016-05-24
 * Time: 13:17
 */

namespace App\Modules\Channel\Adapter;

use App\Models\OrderModel;
use Illuminate\Support\Facades\DB;

set_time_limit(1800);

Class AliexpressAdapter implements AdapterInterface
{
    const GWURL = 'gw.api.alibaba.com';
    private $_access_token;         //获取数据令牌
    private $_refresh_token;        //刷新令牌
    private $_access_token_date;    //获取令牌时间
    private $_appkey;               //应用key
    private $_appsecret;            //应用密匙
    private $_returnurl;            //回传地址
    private $_version = 1;
    private $_aliexpress_member_id;

    public function __construct($config)
    {
        $this->_appkey = $config["appkey"];
        $this->_appsecret = $config["appsecret"];
        $this->_returnurl = $config["returnurl"];
        $this->_access_token_date = $config["access_token_date"];
        $this->_refresh_token = $config["refresh_token"];
        $this->_aliexpress_member_id = $config['aliexpress_member_id'];
        $access_token = $this->isResetAccesstoken();   //是否过期的accesstoken
        $this->_access_token = $access_token == false ? $config["access_token"] : $access_token;

    }


    public function listOrders($startDate, $endDate, $status = [], $perPage = 10)
    {
        $orders = [];
        foreach ($status as $orderStatus) {
            $pageTotalNum = 1;
            for ($i = 1; $i <= $pageTotalNum; $i++) {
                $startDate = empty($startDate) ? date("m/d/Y H:i:s", strtotime('-30 day')) : date("m/d/Y H:i:s",
                    strtotime($startDate));
                $endDate = empty($endDate) ? date("m/d/Y H:i:s", strtotime('-12 hours')) : date("m/d/Y H:i:s",
                    strtotime($endDate));
                $param = "page=" . $i . "&pageSize=" . $perPage . "&orderStatus=" . $orderStatus . "&createDateStart=" . rawurlencode($startDate) . "&createDateEnd=" . rawurlencode($endDate);
                $orderjson = $this->getJsonData('api.findOrderListQuery', $param);
                $orderList = json_decode($orderjson, true);
                unset($orderjson);
                if (isset($orderList['orderList'])) {
                    if ($i == 1) {
                        $pageTotalNum = ceil($orderList['totalItem'] / $perPage); //重新生成总页数
                    }
                    foreach ($orderList['orderList'] as $list) {
                        $thisOrder = orderModel::where('channel_ordernum',
                            $list['orderId'])->first();     //获取详情之前 进行判断是否存在 存在就没必要调API了
                        if ($thisOrder) {
                            continue;
                        }
                        $param = "orderId=" . $list['orderId'];
                        $orderjson = $this->getJsonData('api.findOrderById', $param);
                        $orderDetail = json_decode($orderjson, true);
                        if ($orderDetail) {
                            $order = $this->parseOrder($list, $orderDetail);
                            if ($order) {
                                $orders[] = $order;
                            }
                        } else {
                            continue;
                        }
                    }

                } else {
                    break;
                }
            }

            // if ($createDateStart && $createDateEnd) $param .= "&createDateStart=" . rawurlencode($createDateStart) . "&createDateEnd=" . rawurlencode($createDateEnd);

        }

        return $orders;
    }

    public function listOrdersOther($startDate, $endDate, $status, $page = 1, $perPage = 10)
    {

        $startDate = empty($startDate) ? date("m/d/Y H:i:s", strtotime('-30 day')) : date("m/d/Y H:i:s",
            strtotime($startDate));
        $endDate = empty($endDate) ? date("m/d/Y H:i:s", strtotime('-12 hours')) : date("m/d/Y H:i:s",
            strtotime($endDate));
        $param = "page=" . $page . "&pageSize=" . $perPage . "&orderStatus=" . $status . "&createDateStart=" . rawurlencode($startDate) . "&createDateEnd=" . rawurlencode($endDate);

        //echo $param.'<br/>';
        $orderjson = $this->getJsonData('api.findOrderListQuery', $param);
        return json_decode($orderjson, true);
    }


    public function getOrder($orderID)
    {
        $param = "orderId=" . $orderID;
        $orderjson = $this->getJsonData('api.findOrderById', $param);
        return json_decode($orderjson, true);
    }

    public function returnTrack()
    {
        echo 'returnTrack';
    }


    public function parseOrder($list, $orderDetail)
    {


        $orderInfo = array();
        $productInfo = array();

        $ship_price = 0;
        $orderProductArr = $list ["productList"][0];
        $order_remark = array();
        foreach ($list ["productList"] as $p) {
            if (isset($p['memo']) && !empty($p['memo'])) {
                $order_remark[$p['childId']] = $p['memo']; //带ID进去吧
            }
            if (trim($p['logisticsServiceName']) != "Seller's Shipping Method") {
                $orderProductArr ["logisticsServiceName"] = $p['logisticsServiceName'];
            }
            $ship_price = $p["logisticsAmount"] ["amount"]; //多个sku的运费 不进行叠加了 因为这个时候就是总运费了
        }

        $orderInfo['channel_ordernum'] = $list['orderId'];
        $orderInfo["email"] = isset($list["buyerInfo"]["email"]) ? $list["buyerInfo"]["email"] : '';
        $orderInfo['amount'] = $list ["payAmount"] ["amount"];;
        $orderInfo['currency'] = $list["payAmount"] ["currencyCode"];
        $orderInfo['payment'] = $list['paymentType'];
        $orderInfo['amount_shipping'] = $ship_price;
        $orderInfo['shipping'] = $orderProductArr['logisticsServiceName'];
        $orderInfo['remark'] = $order_remark ? addslashes(implode('<br />', $order_remark)) : ''; //订单备注
        $orderInfo['shipping_firstname'] = $orderDetail['buyerInfo']['firstName'];
        $orderInfo['shipping_lastname'] = $orderDetail['buyerInfo']['lastName'];
        $orderInfo['shipping_address'] = $orderDetail ["receiptAddress"] ["detailAddress"];
        $orderInfo['shipping_address1'] = isset($orderDetail ["receiptAddress"] ["address2"]) ? $orderDetail ["receiptAddress"] ["address2"] : '';
        $orderInfo['shipping_city'] = $orderDetail ["receiptAddress"] ["city"];
        $orderInfo['shipping_state'] = $orderDetail ["receiptAddress"] ["province"];
        $orderInfo['shipping_country'] = $orderDetail ["receiptAddress"] ["country"];
        $orderInfo['shipping_zipcode'] = $orderDetail ["receiptAddress"] ["zip"];
        $orderInfo['status'] ='PAID';

        $mobileNo = isset($orderDetail ["receiptAddress"] ["mobileNo"]) ? $orderDetail ["receiptAddress"] ["mobileNo"] : '';
        $phoneCountry = isset($orderDetail ["receiptAddress"] ["phoneCountry"]) ? $orderDetail ["receiptAddress"] ["phoneCountry"] : '';
        $phoneArea = isset($orderDetail ["receiptAddress"] ["phoneArea"]) ? $orderDetail ["receiptAddress"] ["phoneArea"] : '';
        $phoneNumber = isset($orderDetail ["receiptAddress"] ["phoneNumber"]) ? $orderDetail ["receiptAddress"] ["phoneNumber"] : '';
        $phoneNumber = $phoneCountry . "-" . $phoneArea . "-" . $phoneNumber;
        $orderInfo['shipping_phone'] = $mobileNo != "" ? $mobileNo : $phoneNumber;
        $orderInfo['payment_date'] = $this->getPayTime($list['gmtPayTime']);
        $orderInfo['aliexpress_loginId'] = $orderDetail['buyerInfo']['loginId'];


        $childProductArr = $orderDetail['childOrderList'];
        foreach ($childProductArr as $childProArr) {

            $skuCode = trim($childProArr ["skuCode"]);
            $n = strpos($skuCode, '*');
            $sku_new = $n !== false ? substr($skuCode, $n + 1) : $skuCode;
            $n = strpos($sku_new, '#');
            $sku_new = $n !== false ? substr($sku_new, 0, $n) : $sku_new;
            $sku_new = str_ireplace('{YY}', '', $sku_new);
            unset($qty);
            $qty = 1;
            if (strpos($sku_new, '(') !== false) {
                $matches = array();
                preg_match_all("/(.*?)\([a-z]?([0-9]*)\)?/i", $sku_new, $matches);
                $sku_new = trim($matches[1][0]);
                $qty = trim($matches[2][0]) ? trim($matches[2][0]) : 1;
            }
            $productInfo[$sku_new]['channel_sku'] = trim($childProArr ["skuCode"]);
            $productInfo[$sku_new]["sku"] = $sku_new;
            $productInfo[$sku_new]["price"] = $qty ? $childProArr["productPrice"]["amount"] / $qty : $childProArr["productPrice"]["amount"];

            $productInfo[$sku_new]["quantity"] = isset($productInfo[$sku_new]["quantity"]) ? $productInfo[$sku_new]["quantity"] : 0;
            $productInfo[$sku_new]["quantity"] += $qty ? $childProArr["productCount"] * $qty : $childProArr["productCount"];
            $productInfo[$sku_new]['currency'] = $childProArr['initOrderAmt']['currencyCode'];
            $productInfo[$sku_new]['orders_item_number'] = $childProArr['productId'];

            if (!empty($order_remark) && !empty($order_remark[$childProArr['id']])) { // --各SKU相应的备注信息
                $productInfo[$sku_new]["remark"] = isset($productInfo[$sku_new]["remark"]) ? $productInfo[$sku_new]["remark"] . ' ' . $order_remark[$childProArr['id']] : $order_remark[$childProArr['id']]; //备注信息
            }
        }
        foreach ($productInfo as $pro) {
            $orderInfo['items'][] = $pro;
        }

        return $orderInfo;

    }

    /**
     * @param $paytime
     * @return bool|string
     */
    public function getPayTime($paytime)
    {
        $str = mb_substr($paytime, 0, 14);
        return date('Y-m-d H:i:s', strtotime($str));
    }


    /**
     * 使用access_token 令牌获取数据
     * @param string $action api动作
     * @param string $parameter 传输参数
     * @param boolen $_aop_signature 是否需要签名
     */
    public function getJsonData($action, $parameter, $_aop_signature = true)
    {
        //接口URL
        $app_url = "http://" . self::GWURL . "/openapi/";
        //apiinfo	aliexpress.open
        $apiInfo = "param2/" . $this->_version . "/aliexpress.open/{$action}/" . $this->_appkey;

        //参数
        $app_parameter_url = ($parameter ? "$parameter&" : '') . "access_token=" . $this->_access_token;
        $sign_url = '';
        if ($_aop_signature) { //是否需要签名
            //获取对应URL的签名
            $sign = $this->getApiSign($apiInfo, $app_parameter_url);
            $sign_url = "&_aop_signature=$sign"; //签名参数
        }
        //组装URL
        $get_url = $app_url . $apiInfo . '?' . $app_parameter_url . $sign_url;
        //if ( $this->debug ) echo $get_url. "\n";
        $result = $this->getCurlData($get_url);
        return $result;
    }


    /**
     * Curl http Get 数据
     * 使用方法：
     */
    public function getCurlData($remote_server)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $remote_server);
        //curl_setopt ( $ch, CURLOPT_HTTPHEADER, array('Content-Type: application/x-www-form-urlencoded; charset=utf-8'));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); // 获取数据返回
        curl_setopt($ch, CURLOPT_BINARYTRANSFER, true); // 在启用 CURLOPT_RETURNTRANSFER 时候将获取数据返回
        curl_setopt($ch, CURLOPT_TIMEOUT, 100);
        $output = curl_exec($ch);
        if (curl_errno($ch)) {
            // $this->setCurlErrorLog(curl_error ( $ch ));
            die(curl_error($ch)); //异常错误
        }
        curl_close($ch);
        return $output;
    }

    /**
     * Curl http Post 数据
     * 使用方法：
     * $post_string = "app=request&version=beta";
     */
    public function postCurlData($remote_server, $post_string)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $remote_server);
        // curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/x-www-form-urlencoded'));
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post_string);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 100);
        $data = curl_exec($ch);
        if (curl_errno($ch)) {
            //  $this->setCurlErrorLog(curl_error ( $ch ));
            die(curl_error($ch)); //异常错误
        }
        curl_close($ch);
        return $data;
    }


    /**
     *
     * API签名算法主要是使用urlPath和请求参数作为签名因子进行签名，主要针对api 调用
     * @param $apiInfo URL信息
     * @param $strcode 参数
     */
    public function getApiSign($apiInfo, $strcode)
    {
        $code_arr = explode("&", $strcode);//去掉&
        $newcode_arr = array();
        foreach ($code_arr as $key => $val) {
            $code_narr = explode("=", $val);//分割=
            $newcode_arr [$code_narr [0]] = $code_narr [1];//重组数组
        }
        ksort($newcode_arr);//排序
        $sign_str = "";
        foreach ($newcode_arr as $key => $val) {//获取值
            $sign_str .= $key . rawurldecode($val);
        }
        $sign_str = $apiInfo . $sign_str;//连接
        //加密
        //if ( $this->debug ) echo $sign_str. "\n";
        $code_sign = strtoupper(bin2hex(hash_hmac("sha1", $sign_str, $this->_appsecret, true)));
        return $code_sign;
    }

    /**
     * 获取acees_token
     * 判断access_token是否过期(10小时)
     */
    public function isResetAccesstoken()
    {
        $now = date("Y-m-d H:i:s");
        $hours = (strtotime($now) - strtotime($this->_access_token_date)) / 60 / 60;
        if ($hours > 9.5) { //大于10小时(提前半小时)
            $json = $this->resetAccessToken(); //获取最新的access_token
            $data = json_decode($json, true);
            DB::table('channel_accounts')->where('aliexpress_member_id', $this->_aliexpress_member_id)->update([
                'aliexpress_access_token' => $data["access_token"],
                'aliexpress_access_token_date' => date('Y-m-d H:i:s')
            ]);
            return $data["access_token"];
        } else {
            return false;
        }
    }

    /**
     *
     * refreshToken换取accessToken  POST https
     * @param string $refresg_token
     */
    public function resetAccessToken()
    {
        $serverurl = "https://" . self::GWURL . "/openapi/http/" . $this->_version . "/system.oauth2/getToken/" . $this->_appkey . "";
        $refresh_token = $this->_refresh_token;
        $postdata = "grant_type=refresh_token&client_id=" . $this->_appkey . "&client_secret=" . $this->_appsecret . "&refresh_token=" . $refresh_token . "";
        return $this->postCurlHttpsData($serverurl, $postdata);
    }

    /**
     * Curl https Post 数据
     * 使用方法：
     * $post_string = "app=request&version=beta";
     *
     */
    public function postCurlHttpsData($url, $data)
    { // 模拟提交数据函数
        $curl = curl_init(); // 启动一个CURL会话
        curl_setopt($curl, CURLOPT_URL, $url); // 要访问的地址
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0); // 对认证证书来源的检查
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0); // 从证书中检查SSL加密算法是否存在
        curl_setopt($curl, CURLOPT_USERAGENT, $_SERVER ['HTTP_USER_AGENT']); // 模拟用户使用的浏览器
        curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1); // 使用自动跳转
        curl_setopt($curl, CURLOPT_AUTOREFERER, 1); // 自动设置Referer
        curl_setopt($curl, CURLOPT_POST, 1); // 发送一个常规的Post请求
        curl_setopt($curl, CURLOPT_POSTFIELDS, $data); // Post提交的数据包
        curl_setopt($curl, CURLOPT_TIMEOUT, 30); // 设置超时限制防止死循环
        curl_setopt($curl, CURLOPT_HEADER, 0); // 显示返回的Header区域内容
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1); // 获取的信息以文件流的形式返回
        $tmpInfo = curl_exec($curl); // 执行操作
        if (curl_errno($curl)) {
            // $this->setCurlErrorLog(curl_error ( $curl ));
            die(curl_error($curl)); //异常错误
        }
        curl_close($curl); // 关闭CURL会话
        return $tmpInfo; // 返回数据
    }
   
    public function getMessages(){
        
    }
    public function sendMessages(){
        
    }


}