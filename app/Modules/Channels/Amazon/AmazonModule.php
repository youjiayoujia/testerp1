<?php

namespace App\Modules\Channels\Amazon;

use App\Modules;

class AmazonModule extends BaseChannelModule
{
    private $serviceUrl;

    function __construct()
    {
        //北美亚马逊地址
        $this->serviceUrl = "https://mws.amazonservices.com/Orders/2013-09-01?";

        const AWS_SECRET_ACCESS_KEY = ''
    }

    public function createSignatureV2()
    {
        $url = $this->urlParse();
        $sign  = 'GET' . "\n";
        $sign .= $url['host'] . "\n";
        $sign .= $url['path'] . "\n";
        $sign .= $arr;

        $signature = hash_hmac("sha256", $sign, , true);
        $signature = urlencode(base64_encode($signature));
    }

    /**
     * 将url参数数组转成url格式 
     *
     * @param none
     * @return string
     *
     */
    private function signArrToString()
    {
        $_config = $this->createSignArr();
        $url = array();
        foreach ($_config as $key => $val) {
            $key = str_replace("%7E", "~", rawurlencode($key));
            $val = str_replace("%7E", "~", rawurlencode($val));
            $url[] = "{$key}={$val}";
        }
        sort($url);
        $string = implode('&', $url);

        return $string;
    }

    /**
     * 对url进行解析 
     *
     * @return array  | scheme,host,path
     *
     */
    private function urlParse()
    {
        return parse_url($this->serviceUrl);
    }

    /**
     * 生成url请求所需的所有参数 
     *
     * @param none
     * @return arr|$_config
     *
     */
    private function createSignArr()
    {
        $_config = config('setting.channel.amazon');
        $_config['Action'] = 'ListOrders';
        $_config['OrderStatus.Status.1'] = 'Unshipped';
        $_config['OrderStatus.Status.2'] = 'PartiallyShipped';
        $_config['CreatedAfter'] = gmdate("Y-m-d\TH:i:s.\\0\\0\\0\\Z", time()-24*60*60);

        return $_config;
    }

    public function getOrder()
    {

    }

    public function orderLists()
    {

    }
}