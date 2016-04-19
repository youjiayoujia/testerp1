<?php
// $param = array();
// $param['AWSAccessKeyId']   = 'AKIAIOSFODNN7EXAMPLE'; 
// $param['Action']           = 'DescribeJobFlows'; 
// $param['SignatureMethod']  = 'HmacSHA256'; 
// $param['SignatureVersion'] = '2'; 
// $param['Timestamp']        = "2011-10-03T15:19:30";
// $param['Version']          = '2009-03-31'; 

// $url = array();
// foreach ($param as $key => $val) {
//     $key = str_replace("%7E", "~", rawurlencode($key));
//     $val = str_replace("%7E", "~", rawurlencode($val));
//     $url[] = $key."=".$val;
// }
// sort($url);

// $arr   = implode('&', $url);
// var_dump($arr);
// $sign  = 'GET' . "\n";
// $sign .= 'elasticmapreduce.amazonaws.com' . "\n";
// $sign .= "/\n";
// $sign .= $arr;

// $signature = hash_hmac("sha256", $sign, 'wJalrXUtnFEMI/K7MDENG/bPxRfiCYEXAMPLEKEY', true);
// $signature = urlencode(base64_encode($signature));
// var_dump($signature);exit;
// $link  = "https://mws.amazonservices.jp/Orders/2013-09-01?";
// $link .= $arr . "&Signature=" . $signature;
// var_dump($link);exit;
// i91nKc4PWAt0JJIdXwz9HxZCJDdiy6cf%2FMj6vPxyYIs%3D

$param = array();
$param['AWSAccessKeyId']   = 'AKIAJE7QKBLWVEGMZRJQ'; 
$param['Action']           = 'ListOrders'; 
$param['SellerId']         = 'A3THBIK7QYKUUV'; 
$param['SignatureMethod']  = 'HmacSHA256'; 
$param['SignatureVersion'] = '2'; 
$param['Timestamp']        = gmdate("Y-m-d\TH:i:s", time());
$param['Version']          = '2013-09-01'; 
$param['MarketplaceId.Id.1']    = 'ATVPDKIKX0DER'; 
$param['PaymentMethod.1']    = 'COD';
$param['CreatedAfter']     = gmdate("Y-m-d\TH:i:s", time()-48*60*60);
$param['FulfillmentChannel.Channel.1'] = 'MFN';
$param['OrderStatus.Status.1'] = 'Unshipped';
$url = array();
foreach ($param as $key => $val) {
    $key = str_replace("%7E", "~", rawurlencode($key));
    $val = str_replace("%7E", "~", rawurlencode($val));
    $url[] = "{$key}={$val}";
}
sort($url);
var_dump($url);
$arr   = implode('&', $url);
var_dump($arr);
$sign  = "GET" . "\n";
$sign .= "mws.amazonservices.com" . "\n";
$sign .= "/Orders/2013-09-01" . "\n";
$sign .= "/\n";
$sign .= $arr;
var_dump($sign);
$signature = hash_hmac("sha256", $sign, 'gH13dKCgTtBloKPhHFIBmDTv/sbNoUNJkWIMK/Je', true);
$signature = urlencode(base64_encode($signature));
var_dump($signature);
$link  = "https://mws.amazonservices.com/Orders/2013-09-01?";
$link .= $arr . "&Signature=" . $signature;
var_dump($link);exit;

https://elasticmapreduce.amazonaws.com?AWSAccessKeyId=AKIAIOSFODNN7EXAMPLE&Action=DescribeJobFlows&SignatureMethod=HmacSHA256&SignatureVersion=2&Timestamp=2011-10-03T15%3A19%3A30&Version=2009-03-31&Signature=0P6Oo3kbTvLfyVucf6xTytPanewAk73hDlhe%2BNVkaGA%3D
// $param = array();
// $param['AWSAccessKeyId']   = 'AKIAJE7QKBLWVEGMZRJQ'; 
// $param['Action']           = 'ListOrders'; 
// $param['SellerId']         = 'A3THBIK7QYKUUV'; 
// $param['SignatureMethod']  = 'HmacSHA256'; 
// $param['SignatureVersion'] = '2'; 
// $param['Timestamp']        = gmdate("Y-m-d\TH:i:s.\\0\\0\\0\\Z", time());
// $param['Version']          = '2013-09-01'; 
// $param['MarketplaceId.Id.1']    = 'ATVPDKIKX0DER'; 
// $param['PaymentMethod.1']    = 'COD';
// $param['OrderStatus.Status.1'] = 'Unshipped';
// $param['CreatedAfter']     = gmdate("Y-m-d\TH:i:s", time()-48*60*60);
// $param['FulfillmentChannel.Channel.1'] = 'MFN';
// $url = array();
// foreach ($param as $key => $val) {
//     $key = str_replace("%7E", "~", rawurlencode($key));
//     $val = str_replace("%7E", "~", rawurlencode($val));
//     $url[] = "{$key}={$val}";
// }
// sort($url);
// $arr   = implode('&', $url);
// var_dump($arr);
// $sign  = 'GET' . "\n";
// $sign .= 'mws.amazonservices.jp' . "\n";
// $sign .= '/Orders/2013-09-01' . "\n";
// $sign .= $arr;

// $signature = hash_hmac("sha256", $sign, 'gH13dKCgTtBloKPhHFIBmDTv/sbNoUNJkWIMK/Je', true);
// $signature = urlencode(base64_encode($signature));

// $link  = "https://mws.amazonservices.jp/Orders/2013-09-01?";
// $link .= $arr . "&Signature=" . $signature;
// var_dump($link);exit;

//         $ch = curl_init($link);
//         curl_setopt($ch,CURLOPT_HEADER,0);
//         curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
//         //curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);  

//         $res = curl_exec($ch);
//         curl_close($ch);
//         var_dump($res);
//         $res1 = json_decode($res);

//         var_dump($res1);exit;