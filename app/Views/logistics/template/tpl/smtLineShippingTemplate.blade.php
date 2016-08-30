<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>中国邮政平常小包+面单（SMT线上发货）</title>
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
    <div style="width:380px;border:1px solid #000;border-bottom:none;">
        <p style="float:left;width:140px;height:90px;border-left:1px solid #000;">
            <img src="'.site_url('attachments').'/images/post_logo.jpg" style="width:140px;height:50px;"/>
            <span style="font-size:10px;">Small Packet By Air</span>
            <br/>
			 		    <span style="display:inline-block;width:55px;height:22px;border:2px solid #000;margin-left:40px;text-align:center;font-size:18px;font-weight:bold;">
			 		      '.$allParamArr['country_code'].$allParamArr['country_Info']['sort_code'].'
			 		    </span>
        </p>

        <p style="float:left;width:238px;height:90px;text-align:center;border-right:1px solid #000;">
			 		      <span style="display:inline-block;margin-top:12px;margin-left:40px;">
		 		              <img src="'.site_url('default/third_party').'/chanage_code/barcode/html/image.php?code=code128&o=2&t=40&r=1&text='.$allParamArr['ordersInfo']['orders_shipping_code'].'&f1=-1&f2=8&a1=&a2=B&a3="/>
		 		              '.$allParamArr['ordersInfo']['orders_shipping_code'].'
		 		          </span>
            <span style="font-weight:bold;font-size:11px;display:inline-block;">Untracked 平小包</span>
        </p>

        <p style="float:left;width:140px;">
			 		     <span style="width:140px;display:inline-block;height:97px;border-left:1px solid #000;border-bottom:1px solid #000;font-size:11px;padding-left:4px;">
			 		       From:<br/>
			 		       '.$allParamArr['senderInfo']['street'].'<br/>
			 		       <b style="font-weight:bold;">Phone:'.$allParamArr['senderInfo']['mobilePhone'].'</b>
			 		     </span>
			 		     <span style="width:140px;font-size:16px;line-height:29px;background:#fff;display:inline-block;border-left:1px solid #000;">
			 		       	'.$allParamArr['warehouse_flag'].'
			 		     </span>
        </p>

        <p style="float:left;width:238px;border:1px solid #000;border-bottom:none;font-size:12px;">
            <span style="font-weight:bold;font-size:12px;">Ship To:</span><br/>
            '.$allParamArr['ordersInfo']['buyer_name'].'<br/>
            '.$allParamArr['ordersInfo']['buyer_address_1'].' '.$allParamArr['ordersInfo']['buyer_address_2'].'<br/>
            '.$allParamArr['ordersInfo']['buyer_city'].'<br/>
            '.$allParamArr['ordersInfo']['buyer_state'].'<br/>
            '.$allParamArr['buyerCountry'].' '.$allParamArr['ordersInfo']['buyer_zip'].'<br/>
            phone:'.$allParamArr['ordersInfo']['buyer_phone'].'&nbsp;&nbsp; &nbsp; <span style="font-size:16px;">'.$allParamArr['country_cn'].'</span>
            &nbsp;&nbsp; &nbsp;'.$allParamArr['country_Info']['zone'].'
        </p>
    </div>

    <table border="0" style="width:382px;height:110px;" cellspacing="0" cellpadding="0">

        <tr style="height:15px;font-weight:bold;font-size:10px;text-align:center;">
            <td width="70%" style="border-right:none;">
                Description of Contents
            </td>
            <td width="15%" style="border-right:none;">
                Kg
            </td>
            <td width="15%">
                Val(USD $)
            </td>
        </tr>
        '.$allParamArr['trInfo'].'
        <tr height="15" style="font-size:12px;">
            <td width="70%" style="border-right:none;font-size:12px;">
                Totalg Gross Weight(kg)
            </td>
            <td width="15%" style="border-right:none;">'.$allParamArr['productsInfo']['total_weight'].'</td>
            <td width="15%">'.$allParamArr['productsInfo']['total_value'].'</td>
        </tr>
        <tr height="55">
            <td colspan="3" style="border-bottom:1px solid #000;font-size:9px;">
                I the undersigned,certify that the particulars given in this declaration are correct and this item
                does not contain any dangerous articles prohibited by legislation or by postal or customers
                regulations.<br/>
                <span style="font-weight:bold;font-size:12px;">Sender\'s signiture& Data Signed :SLME</span>
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
		 		             <span style="font-weight:bold;display:inline-block;width:60px;line-height:15px;height:15px;font-size:14px;">
		 		             	CN22
		 		             </span>
            </td>
        </tr>
    </table>
    <div style="width:382px;height:40px;margin:0 auto;font-size:10px;white-space:normal;overflow:hidden;">
        <span style="font-size:12px;font-weight:bold;">【'.$allParamArr['ordersInfo']['shipmentAutoMatched'].'】</span>'.$allParamArr['skuInfo'].'
    </div>
</div>
</body>
</html>