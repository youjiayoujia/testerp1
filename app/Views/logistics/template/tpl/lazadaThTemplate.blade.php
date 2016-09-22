<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>lazada泰国面单</title>
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
        height: 128mm;
        overflow: hidden;
        margin-bottom: 2px;
        border-bottom: 1px solid #000;
    }

    td {
        border: 1px solid #000;
        border-bottom: none;
    }
</style>
<div id="main_frame_box">

    <table border="0" style="width:382px;height:128mm;" cellspacing="0" cellpadding="0">
        <tr height="100">

            <td style="border-right:none;width:35%;text-align:center;font-weight:bold;">
                '.$allParamArr['ordersInfo']['buyer_id'].'<br>【'.$allParamArr['ordersInfo']['shipmentAutoMatched'].'】
            </td>

            <td style="border-right:none;width:58%;text-align:center;font-weight:bold;">
                EMS Tracking No:<br/>

                <p style="font-size:5px;">&nbsp;</p>
                <img src="'.site_url('default/third_party').'/chanage_code/barcode/html/image.php?code=code128&o=2&t=30&r=1&text='.$allParamArr['ordersInfo']['orders_shipping_code'].'&f1=-1&f2=8&a1=&a2=B&a3="/>
                <br/>
                '.$allParamArr['ordersInfo']['orders_shipping_code'].'
            </td>

            <td style="width:7%;text-align:center;font-weight:bold;">
                TH1
            </td>

        </tr>
        <tr height="70">
            <td colspan="3" style="text-align:center;">
                Package No:<br/>

                <p style="font-size:5px;">&nbsp;</p>
                <img src="'.site_url('default/third_party').'/chanage_code/barcode/html/image.php?code=code128&o=2&t=25&r=1&text='.$allParamArr['pagenumber'].'&f1=-1&f2=8&a1=&a2=B&a3="/>
                <br/>
                <spans style="font-weight:bold;">'.$allParamArr['pagenumber'].'</span>
            </td>
        </tr>
        <tr height="200" style="overflow:hidden;">
            <td style="border-right:none;width:35%;text-align:center;font-weight:bold;">
                '.$allParamArr['shipName'].'<br/>
                ชื่อบริษัท:
                กรณีนำจ่ายไม่ได้ กรุณาส่งคืน ศป.EMS 10020<br/>
                <img src="'.site_url('attachments').'/images/TH_label_lzd_log.png" style="width:120px;"/>
            </td>
            <td colspan="2">
                '.$allParamArr['ordersInfo']['buyer_name'].'<br/>
                '.$allParamArr['ordersInfo']['buyer_address_1'].' '.$allParamArr['ordersInfo']['buyer_address_2'].'<br/>
                '.$allParamArr['ordersInfo']['buyer_city'].'<br/>
                '.$allParamArr['ordersInfo']['buyer_state'].'<br/>
                '.$allParamArr['buyerCountry'].' &nbsp;&nbsp;'.$allParamArr['ordersInfo']['buyer_zip'].'<br/>
                '.$allParamArr['ordersInfo']['buyer_phone'].'

            </td>
        </tr>
        <tr height="50" style="font-weight:bold;">
            <td style="border-right:none;width:35%;">
                ไม่เก็บเงินค่าสินค้า
            </td>
            <td colspan="2">
                '.$allParamArr['ordersInfo']['buyer_zip'].'
            </td>
        </tr>
    </table>

</div>
</body>
</html>