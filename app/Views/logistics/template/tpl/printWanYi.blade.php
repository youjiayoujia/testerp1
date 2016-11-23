<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>万邑邮选面单</title>
    <style>
        *{margin:0; padding:0;}
        #main{width:100mm; height:99mm; margin:0 auto;border:1px solid #000; overflow: hidden;}
        body{font-size: 10px;}
        .f_l{float:left;}
        .f_r{float:right;}
        .address tr th{text-align:left;}
        .address tr td{text-align:right;}
    </style>
</head>
<body>
<div id="main">
    <div style="width:100%;height:70px;border-bottom:1px solid #000;">
        <p style="width:100%;height:60%;font-size:14px;font-weight:bold;">
            Track No:{{$model->tracking_no}}
            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            '.$allParamArr['country_code'].'
            &nbsp;&nbsp;
            '.$allParamArr['country_cn'].'
        </p>
        <p style="width:100%;height:40%;font-size:14px;text-align:right;">
            '.$allParamArr['shipmentTitles'].'
        </p>
    </div>
    <div style="width:100%;height:60px;border-bottom:1px solid #000;text-align:center;font-size:14px;">
        <div style="width:100%;height:2px;"></div>
        <img src="{{ route('barcodeGen', ['content' => $model->tracking_no]) }}" />
        <br/>
        Intl Tracking No:{{$model->tracking_no}}
    </div>
    <div style="width:100%;height:180px;border-bottom:1px solid #000;">
        <div style="width:100%;height:120px;overflow:hidden;font-size:12px;">
            <span style="font-weight:bold;">To:</span>
            {{ $model->shipping_firstname . ' ' . $model->shipping_lastname }}<br/>
            {{ $model->shipping_address . ' ' . $model->shipping_address1 }}<br/>
            {{ $model->shipping_city . ',' . $model->shipping_state }}<br/>'
            {{ $model->shipping_zipcode . ',' . $model->shipping_phone }}<br/>
            {{ $model->shipping_country }}
        </div>
        <div style="width:100%;height:60px;overflow:hidden;font-size:12px;">
            <span style="font-weight:bold;font-size:12px;">From:</span>{{$model->getpostconfig ? $model->getpostconfig->consumer_from : ''}}
            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            <span style="font-weight:bold;font-size:12px;">CN:</span>'.$allParamArr['customer_code'][$allParamArr['ordersInfo']['orders_warehouse_id']].'
            &nbsp;&nbsp;&nbsp;&nbsp;
            <span style="font-weight:bold;font-size:12px;">渠道:</span>{{ $model->logistics_id }}
            &nbsp;&nbsp;&nbsp;&nbsp;
            <span style="font-weight:bold;font-size:12px;">Tel:</span>{{$model->getpostconfig ? $model->getpostconfig->consumer_phone : ''}}<br/>
            <span style="font-weight:bold;font-size:12px;">Add:</span>{{$model->getpostconfig ? $model->getpostconfig->consumer_back : ''}}
        </div>
    </div>
    <div style="width:100%;height:70px;">
        <p style="width:50%;height:100%;float:left;text-align:center;font-size:12px;">
            <img src="{{ route('barcodeGen', ['content' => $model->tracking_no]) }}" />
            <br/>
            {{ $model->order ? $model->order->ordernum : '' }}
        </p>
        <p style="width:50%;height:100%;float:left;">
            {{ $model->decleared_cname }}
            {{ $model->decleared_ename }}
        </p>
    </div>
</div>
</body>
</html>