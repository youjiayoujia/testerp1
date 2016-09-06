<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016-07-25
 * Time: 13:40
 */
namespace App\Modules\Paypal;

Class PaypalApi
{
    private $RUL;
    private $VERSION;
    private $PWD;
    private $USER;
    private $SIGNATURE;
    public $httpResponse;

    public function __construct($config)
    {
        $this->RUL = "https://api-3t.paypal.com/nvp";
        $this->VERSION = urlencode('51.0');
        $this->USER = urlencode(trim($config->paypal_account));
        $this->PWD = urlencode(trim($config->paypal_password));
        $this->SIGNATURE = urlencode(trim($config->paypal_token));
    }


    function apiRequest($callName, $TRANSACTIONID)
    {

        $nvpreq = "METHOD=" . $callName . "&VERSION=" . $this->VERSION . "&PWD=" . $this->PWD . "&USER=" . $this->USER . "&SIGNATURE=" . $this->SIGNATURE . "&TRANSACTIONID=" . urldecode(trim($TRANSACTIONID));

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $this->RUL);
        curl_setopt($ch, CURLOPT_VERBOSE, 1);
        curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows; U; Windows NT 5.1; zh- CN; rv:1.9.0.5) Gecko/2008120122 Firefox/3.0.5 FirePHP/0.2.1');
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
        curl_setopt($ch, CURLOPT_CAINFO, base_path('public\cacert.pem'));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_TIMEOUT, 90);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $nvpreq);
        $result = curl_exec($ch);
        if ($result) {
            $result = explode('&', $result);
            $httpResponseArray = array();
            foreach ($result as $str) {
                $strArray = explode('=', $str);
                $httpResponseArray[$strArray[0]] = urldecode($strArray[1]);
            }
            $this->httpResponse = $httpResponseArray;
            if ($callName == 'RefundTransaction') {
                $lastValue = '';
                foreach ($httpResponseArray as $key => $value) {
                    $lastValue .= "$key:$value\n";
                }
                // $this->writeDatelog("/data/smtAPICallLog/CurlErrorLog/paypal/$tokenID.txt", $lastValue);
            }
            return true;
        } else {
            return false;
        }

    }

    /**
     * 退款
     */
    public function apiRefund($callName,$array,$tokenID){
        $tokenArray = $this -> tokens[$tokenID];
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $this -> url);
        curl_setopt($ch, CURLOPT_VERBOSE, 1);
        curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows; U; Windows NT 5.1; zh- CN; rv:1.9.0.5) Gecko/2008120122 Firefox/3.0.5 FirePHP/0.2.1');

        //curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        //curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);

        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
        //curl_setopt($ch, CURLOPT_SSLVERSION, 3);
        curl_setopt($ch, CURLOPT_CAINFO,'/data/cacert.pem');

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_TIMEOUT, 90);

        //proxy
        //helen5106
        //curl_setopt($ch, CURLOPT_HTTPPROXYTUNNEL, 0);
        //curl_setopt($ch, CURLOPT_PROXYTYPE, CURLPROXY_SOCKS5);
        //curl_setopt($ch, CURLOPT_PROXY, '127.0.0.1:8000');

        $newArray = array();
        foreach($array as $k => $v)
        {
            $newArray[] = $k.'='.urlencode(trim($v));
        }
        if(count($newArray)>0){
            $nvpreq = "METHOD=".$callName."&VERSION=".$this -> version."&PWD=".urlencode(trim($tokenArray['paypal_password']))."&USER=".urlencode(trim($tokenArray['paypal_account']))."&SIGNATURE=".urlencode(trim($tokenArray['paypal_token']))."&".join('&',$newArray);
        }else{
            $nvpreq = "METHOD=".$callName."&VERSION=".$this -> version."&PWD=".urlencode(trim($tokenArray['paypal_password']))."&USER=".urlencode(trim($tokenArray['paypal_account']))."&SIGNATURE=".urlencode(trim($tokenArray['paypal_token']));
        }
        curl_setopt($ch, CURLOPT_POSTFIELDS, $nvpreq);

        $result = curl_exec($ch);
        //$error = curl_error($ch);
        //var_dump($error);

        if($result)
        {
            $resultArray = explode('&',$result);
            $httpResponseArray = array();
            foreach($resultArray as $str)
            {
                $strArray = explode('=',$str);
                $httpResponseArray[$strArray[0]] = urldecode($strArray[1]);
            }

            if(0 == sizeof($httpResponseArray) || !array_key_exists('ACK',$httpResponseArray))
            {
                return false;
            }
            else
            {
                $this -> httpResponse = $httpResponseArray;
                //保存退款返回的记录
                if( $callName == 'RefundTransaction' ){
                    //$nowTime = date ( "H-i-s", time () );
                    //@mkdir ( '/data/smtAPICallLog/CurlErrorLog/paypal', 0777, true );
                    foreach($httpResponseArray as $key => $value) {
                        $lastValue .= "$key:$value\n";
                    }

                    $this->writeDatelog("/data/smtAPICallLog/CurlErrorLog/paypal/$tokenID.txt", $lastValue);
                    //print_r($httpResponseArray);

                }
                return true;
            }
        }
        else
        {
            return false;
        }
    }


}