<?php
/**
 * Created by PhpStorm.
 * User: Norton
 * Date: 2016/10/9
 * Time: 16:32
 */
namespace App\Modules\Alibaba;
class Alibaba {
    public $app_key='1023183';

    public $secret_key='ZCCNtKHIiGG';

    public $params;

    public $ali_url='http://gw.open.1688.com:80';

    /*
     *生成阿里接口访问地址及参数
     */
    public function getRequestUrl(){}

    /**
     * 获取用户token
     */
    public function getToken($code)
    {

    }


    /**
     * http://gw.open.1688.com
     * /auth/authorize.htm?client_id=xxx&site=china&redirect_uri=YOUR_REDIRECT_URL&state=YOUR_PARM&_aop_signature=SIGENATURE
     */
    public function getCode(){

    }

    /**
     * 请求中用到的签名
     */

    public function signatrue() {

    }

    public function getSignature(){
        $sign = '/param2/1/system/currentTime/' . $this->app_key;
    }




}