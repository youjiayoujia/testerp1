<?php

$param = array();
$param['AWSAccessKeyId'] = 'AKIAJE7QKBLWVEGMZRJQ';
$param['Action'] = 'ListOrders';
$param['MarketplaceId.Id.1'] = 'ATVPDKIKX0DER';
//$param['FulfillmentChannel.Channel.1'] = 'MFN';
$param['OrderStatus.Status.1'] = 'Unshipped';
$param['OrderStatus.Status.2'] = 'PartiallyShipped';
$param['SignatureVersion'] = '2';
$param['SellerId'] = 'A3THBIK7QYKUUV';
$param['SignatureMethod'] = 'HmacSHA256';
$param['Timestamp'] = gmdate("Y-m-d\TH:i:s.\\0\\0\\0\\Z", time());
$param['CreatedAfter'] = gmdate("Y-m-d\TH:i:s.\\0\\0\\0\\Z", time()-24*60*60);
$param['Version'] = '2013-09-01';

$url = array();
foreach ($param as $key => $val) {
    $key = str_replace("%7E", "~", rawurlencode($key));
    $val = str_replace("%7E", "~", rawurlencode($val));
    $url[] = "{$key}={$val}";
}

sort($url);

$arr   = implode('&', $url);
$sign  = 'GET' . "\n";
$sign .= 'mws.amazonservices.com' . "\n";
$sign .= '/Orders/2013-09-01' . "\n";
$sign .= $arr;
$signature = hash_hmac("sha256", $sign, 'gH13dKCgTtBloKPhHFIBmDTv/sbNoUNJkWIMK/Je', true);
$signature = urlencode(base64_encode($signature));

$link  = "https://mws.amazonservices.com/Orders/2013-09-01?";
$link .= $arr . "&Signature=" . $signature;
var_dump($link);
$ch = curl_init($link);
curl_setopt($ch,CURLOPT_HEADER,0);
curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);  
$res = curl_exec($ch);
curl_close($ch);

$arr = simplexml_load_string($res);
echo "<pre>";
// print_r($arr->ListOrdersResult->Orders);
foreach($arr->ListOrdersResult->Orders->Order as $key => $value)
{
    print_r($value);
//    echo $value->LatestShipDate."<br/>";
}
echo "</pre>";

// https://mws.amazonservices.jp/Orders/2013-09-01
//   ?AWSAccessKeyId=0PB842EXAMPLE7N4ZTR2
//   &Action=ListOrders
//   &MWSAuthToken=amzn.mws.4ea38b7b-f563-7709-4bae-87aeaEXAMPLE
//   &MarketplaceId.Id.1=A1VC38T7YXB528
//   &FulfillmentChannel.Channel.1=MFN
//   &PaymentMethod.1=COD
//   &PaymentMethod.2=Other
//   &OrderStatus.Status.1=Unshipped
//   &OrderStatus.Status.2=PendingAvailability
//   &SellerId=A2NEXAMPLETF53
//   &Signature=ZQLpf8vEXAMPLE0iC265pf18n0%3D
//   &SignatureVersion=2
//   &SignatureMethod=HmacSHA256
//   &LastUpdatedAfter=2013-08-01T18%3A12%3A21
//   &Timestamp=2013-09-05T18%3A12%3A21.687Z
//   &Version=2013-09-01
//   "continent"     : "America",
//                 "access_key"    : "AKIAJE7QKBLWVEGMZRJQ",
//                 "secret_key"    : "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",
//                 "account_id"    : "A3THBIK7QYKUUV",
//                 "marketplaceids": ["ATVPDKIKX0DER",],
