<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>迪欧比利时邮政渠道面单100*130</title>
</head>
<body>
<style>
    * {
        margin: 0;
        padding: 0;
    }

    .main {
        width: 94mm;
        height: 123mm;
        border: 1px solid black;
        margin: auto;
        font-size: 14px;
        line-height: 14px;
        padding: 2mm;
    }
</style>
<div class="main">
    <table cellspacing=0 cellpadding=0>
        <tr style="display:block;">
            <td style="border:1px solid black;width:48mm;height:30mm" valign="top">
                <img src="'.site_url('attachments').'/images/Bpostlogo1.jpg" style="width:46mm;height:14mm"/>

                <p style="padding:2px;font-size:12px;">
                    If undelivered please return to<br/>ECDC LOGISTICS<br/>Rue de Maastricht 106 4600,Vise Belgium
                </p>
            </td>
            <td style="border:1px solid black;width:40mm;border-left:none;padding:1mm 3mm;text-align:center;" valign="top">
                <img src="'.site_url('attachments').'/images/Bpostlogo2.jpg" style="width:37mm;height:14mm"/>

                <p style="font-weight: bold;font-size:18px;">PB-PP BPI-9572</p>

                <div style="text-align:right;height:10mm;line-height: 50px;">BELGIE(N)-BELGIQUE</div>
            </td>
        </tr>
        <tr>
            <td colspan="2" style="padding:1mm;font-size:15px;line-height: 19px;">
                TO:'.$allParamArr['ordersInfo']['buyer_name'].'<br/>'
                . $allParamArr['ordersInfo']['buyer_address_1'] .' <br/>'. $allParamArr['ordersInfo']['buyer_address_2']
                .'<br/>
                '. $allParamArr['ordersInfo']['buyer_city'] .' '. $allParamArr['ordersInfo']['buyer_state'] .'<br/>
                Zip Code:'.$allParamArr['ordersInfo']['buyer_zip'].'<br/>
                Country:'.$allParamArr['buyerCountry'].'('.$allParamArr['country_code'].')<br/>
                Phone:'.$allParamArr['ordersInfo']['buyer_phone'].'<br>
                Weight:'.sprintf("%01.2f",$allParamArr['productsInfo']['total_weight']).'<br/>
                Ref:'.$allParamArr['ordersInfo']['erp_orders_id'].'
            </td>
        </tr>
        <tr style="margin-top:10px;display:block;width:95mm">
            <td colspan="2" style="border:1px dashed black;padding:1mm;text-align:center;width:95mm">
                <img src="'.site_url('default/third_party').'/chanage_code/barcode/html/image.php?code=code128&o=2&t=50&r=1&text='.$allParamArr['ordersInfo']['orders_shipping_code'].'&f1=-1&f2=8&a1=&a2=B&a3="/>

                <p>'.$allParamArr['ordersInfo']['orders_shipping_code'].'</p>
            </td>
        </tr>
        <tr style="display:block;width:95mm">
            <td colspan="2" style="width:95mm;">
                <div style="border:1px solid black;height:40px;text-align:center;padding-top:15px;margin-top:10px;">
                    <div style="margin:0 auto;border:1px solid black;padding:2mm;text-align:center;width:20px;height;20px;font-size:20px;font-weight: bold;">
                        '.$allParamArr['bilishiArea'].'
                    </div>
                </div>
            </td>
        </tr>
    </table>
</div>
</body>
</html>