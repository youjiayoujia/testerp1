<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>新邮经济小包(SMT线上发货)</title>
</head>
<body>
<style>
    * {
        margin: 0;
        padding: 0;
    }

    body {
        font-family: Arial, Helvetica, sans-serif;
        font-size: 14px;
    }

    #main_frame_box {
        width: 382px;
        margin: 0 auto;
        height: 378px;
        overflow: hidden;
        margin-bottom: 2px;
    }

    td {
        border: 1px solid #000;
        border-bottom: none;
    }
</style>
<div id="main_frame_box">
    <div style="width:379px;height:130px;border:1px solid #000;border-bottom:none;">
        发货标签<br><br>
        &nbsp;&nbsp;&nbsp;&nbsp;
        <img src="'.site_url('default/third_party').'/chanage_code/barcode/html/image.php?code=code128&o=2&t=25&r=2&text=0B04379950000000&f1=-1&f2=8&a1=&a2=B&a3="/>
        <br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;lntl Tracking No：{{ $model->tracking_no }}
    </div>
    <table border="0" style="width:382px;height:155px;" cellspacing="0" cellpadding="0">
        <tr height="68">
            <td colspan="3">
                <p style="width:270px;height:85px;float:left;text-align:left;font-size:11px;">
                    To：{{ $model->shipping_firstname . ' ' . $model->shipping_lastname }}
                    <br/>
                    Shipping:4PX新邮经济小包<br>
                    From：A3 BLDG on 2F in Hekan IZ,Bantianwuhe South Rd Longgang Dist<br/>
                    Tel：{{ $model->shipping_phone }}
                </p>
                {{ $model->country ? $model->country->code : '' }}
            </td>
        </tr>
        <tr height="62">
            <td colspan="3" style="font-size:10px;font-weight:bold;">
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                <img src="'.site_url('default/third_party').'/chanage_code/barcode/html/image.php?code=code128&o=2&t=25&r=1&text={{ $model->tracking_no }}&f1=-1&f2=8&a1=&a2=B&a3="/>
                <br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Tracking No:None
            </td>
        </tr>
        <tr height="90">
            <td colspan="3" style="border-bottom:1px solid #000;font-size:11px;">
                是否带电池：
                @if($model->items)

                @endif
                <br>
                仓库：
                @if($model->warehouse)
                    @if($model->warehouse->name == '深圳仓')
                        {{ '广东省深圳市宝安区深圳国际机场机场四道国内航空货站201-221四方邮局（递四方深圳仓-经济）' }}
                    @elseif($model->warehouse->name == '义乌仓')
                        {{ '义乌市凯吉路208号5号楼1楼（燕文义乌仓-经济）' }}
                    @endif
                @endif
            </td>
        </tr>
    </table>
</div>
</body>
</html>