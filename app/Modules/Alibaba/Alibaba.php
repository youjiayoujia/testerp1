<?php
/**
 * Created by PhpStorm.
 * User: Norton
 * Date: 2016/10/9
 * Time: 16:32
 */
class Alibaba {
    public $app_key='1023183';

    public $secret_key='ZCCNtKHIiGG';

    public $params;

    public $ali_url='http://gw.open.1688.com:80';

    /*
     *请求中用到的签名
     */
    public function signatrue(){}

    /*
     *生成阿里接口访问地址及参数
     */
    public function getRequestUrl(){}
}