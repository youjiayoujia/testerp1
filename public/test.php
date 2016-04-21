<?php
$serviceUrl  = "https://mws.amazonservices.com/Orders/2013-09-01?";
$buf = parse_url($serviceUrl);
$_scheme = $buf['scheme'];
$_host = $buf['host'];
$_path = $buf['path'];

$param = array();
$param['AWSAccessKeyId'] = 'AKIAJE7QKBLWVEGMZRJQ';
$param['Action'] = 'ListOrderItems';
$param['MarketplaceId.Id.1'] = 'ATVPDKIKX0DER';
$param['SignatureVersion'] = '2';
$param['SellerId'] = 'A3THBIK7QYKUUV';
$param['SignatureMethod'] = 'HmacSHA256';
$param['Timestamp'] = gmdate("Y-m-d\TH:i:s.\\0\\0\\0\\Z", time());
$param['CreatedAfter'] = gmdate("Y-m-d\TH:i:s.\\0\\0\\0\\Z", time()-24*60*60);
$param['Version'] = '2013-09-01';
$param['AmazonOrderId'] = '115-8487509-2210624';

$url = array();
foreach ($param as $key => $val) {
    $key = str_replace("%7E", "~", rawurlencode($key));
    $val = str_replace("%7E", "~", rawurlencode($val));
    $url[] = "{$key}={$val}";
}

sort($url);

$arr   = implode('&', $url);

$sign  = 'GET' . "\n";
$sign .= $_host . "\n";
$sign .= $_path . "\n";
$sign .= $arr;

$signature = hash_hmac("sha256", $sign, 'gH13dKCgTtBloKPhHFIBmDTv/sbNoUNJkWIMK/Je', true);
$signature = urlencode(base64_encode($signature));

$link = $serviceUrl . $arr . "&Signature=" . $signature;
// var_dump($link);
$ch = curl_init($link);
curl_setopt($ch,CURLOPT_HEADER,0);
curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);  
$res = curl_exec($ch);
curl_close($ch);

$arr = simplexml_load_string($res);
echo "<pre>";
print_r($arr);

exit;
// print_r($arr->ListOrdersResult->Orders);
foreach($arr->ListOrdersResult->Orders->Order as $key => $value)
{
    print_r($value);
//    echo $value->LatestShipDate."<br/>";
}
echo "</pre>";

// 115-8487509-2210624