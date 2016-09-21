<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>COE土耳其平邮</title>
</head>
<body>
<style type="text/css">
    * {
        margin: 0 auto;
        padding: 0;
    }

    .main {
        border: 1px solid black;
        width: 98.5mm;
        height: 98.5mm;
    }

    .fk {
        display: inline-block;
        width: 10px;
        height: 8px;
        border: 1px solid #000;
        padding-top: 4px;
        line-height: 10px;
    }
</style>
<div class="main">
    <div style="border:1px solid black;border-top:none;border-right:none;width:96.5mm;height:22mm;float:right;">
        <div style="width:14mm;height:22mm;float:left;text-align: center;">
            <p><img src="{{ asset('picture/ttp1.png') }}" style="width:60px;height:55px;"/></p>
            <p><img src="{{ asset('picture/ttp2.png') }}" style="width:25px;height:24px;"/></p>
        </div>
        <div style="width:57mm;height:22mm;float:left;text-align: center;font-size:12px;line-height: 11px;">
            <p>
                <img src="'.site_url('default/third_party').'/chanage_code/barcode/html/image.php?code=code128&o=2&t=30&r=1&text={{ $model->tracking_no }}&f1=-1&f2=8&a1=&a2=B&a3=" style="margin-top:5px;"/>
            </p>
            <p style="font-size:13px;font-weight: bold;margin-top: 1px;">
                {{ $model->tracking_no }}</p>
            <p style="margin-top: 2px;"> Return if undeliverable PO box 5001 istanbul-TURKEY</p>
        </div>
        <div style="width:92px;height:22mm;float:right;text-align: center;">
            <p style="font-size:10px;width:35px;height:35px;float:left;margin-top:20px;">UNTRACK</p>
            <p style="font-size:10px;border:1px solid black;border-right:none;width:35px;height:35px;float:right;margin-top:15px;line-height:10px;">
                Port Payé<br/>Turkey
            </p>
        </div>
    </div>
    <div style="width:97mm;height:72mm;float:right;">
        <div style="width:64.5mm;height:65mm;float:left;margin-top: 2px;line-height: 12px;">
            <table cellspacing="0" cellpadding="0" style="border:1px solid black;width:64.5mm;height:65mm;">
                <tr>
                    <td colspan=3 style="text-align: center;font-size:11px;border-bottom: 1px solid black;height: 7mm;line-height: 12px;">
                        <p style="border-right:1px solid black;height: 7mm;width:25mm;float:left;">
                            CUSTOMS DECLARATION
                        </p>
                        <p style="border-right:1px solid black;height: 7mm;width:25mm;float:left;">
                            May be opened officially
                        </p>
                        <p style="height: 7mm;width:10mm;float:left;line-height: 30px;">
                            CN22
                        </p>
                    </td>
                </tr>
                <tr>
                    <td colspan="3" style="height:4mm;font-size: 11px;line-height: 8px;">
                        DESIGNATED OPERATOR Turkish Post
                    </td>
                </tr>
                <tr>
                    <td colspan="3" style="height:8mm;border-top:1px solid black;border-bottom:1px solid black;font-size: 11px;line-height: 8px;text-align: left;">
                        <table>
                            <tr>
                                <td><span class="fk"></span></td>
                                <td>GIFT</td>
                                <td><span class="fk"></span></td>
                                <td>COMMERIAL SAMPLE</td>
                            </tr>
                            <tr>
                                <td><span class="fk"></span></td>
                                <td>PRINTED</td>
                                <td><span class="fk">√</span></td>
                                <td>OTHERS(tich as appriate)</td>
                            </tr>
                        </table>
                    </td>
                </tr>
                <tr style="font-size:11px;height:7mm;text-align: center;line-height: 12px;">
                    <td style="width:150px;border-right:1px solid black;border-bottom:1px solid black;">
                        QUANTITY AND DETAILED DESCRÎPTiON OF
                    </td>
                    <td style="border-right:1px solid black;border-bottom:1px solid black;">
                        WEIGHT<br/>(KG)
                    </td>
                    <td style="border-bottom:1px solid black;">
                        VALUE<br/>(USD)
                    </td>
                </tr>
                <tr style="height:4mm;font-size: 11px;text-align:center;">
                    @if($model->items)
                        @foreach($model->items as $key => $item)
                            @if($key == 0)
                                <td style="border-right:1px solid black;border-bottom:1px solid black;">
                                    {{ $item->item ? ($item->item->product ? $item->item->product->name : '' ) : '' }}&nbsp;&nbsp;&nbsp;&nbsp;{{ 'x1' }}
                                </td>
                                <td style="border-right:1px solid black;border-bottom:1px solid black;">
                                    {{ $item->quantity * $item->item ? $item->item->weight : '' }}
                                </td>
                                <td style="border-bottom:1px solid black;">
                                    {{ $item->quantity * $item->item ? $item->item->purchase_price : '' }}
                                </td>
                            @endif
                        @endforeach
                    @endif
                </tr>
                <tr style="height:4mm;font-size: 11px;text-align:center;">
                    <td style="border-right:1px solid black;border-bottom:1px solid black;">

                    </td>
                    <td style="border-right:1px solid black;border-bottom:1px solid black;">

                    </td>
                    <td style="border-bottom:1px solid black;">

                    </td>
                </tr>
                <tr style="height:7mm;font-size:11px;line-height: 12px;">
                    <td style="border-right:1px solid black;border-bottom:1px solid black;">
                        if know,HS Tariff number and country of origin of goods.
                    </td>
                    <td style="border-right:1px solid black;border-bottom:1px solid black;">
                        TOTAL<br/>WEIGHT<br/>(KG)
                    </td>
                    <td style="border-bottom:1px solid black;">
                        TOTAL<br/>VALUE<br/>(USD)
                    </td>
                </tr>
                <tr style="height:4mm;font-size:11px;line-height: 11px;">
                    <td style="border-right:1px solid black;border-bottom:1px solid black;">
                        ORIGIN:China
                    </td>
                    @if($model->items)
                        @foreach($model->items as $key => $item)
                            <td style="border-right:1px solid black;border-bottom:1px solid black;text-align:center;">
                                {{ sum($item->quantity * $item->item ? $item->item->weight : '') }}
                            </td>
                            <td style="border-bottom:1px solid black;text-align:center;">
                                {{ sum($item->quantity * $item->item ? $item->item->purchase_price : '') }}
                            </td>
                        @endforeach
                    @endif
                </tr>
                <tr style="font-size:10px;line-height: 10px;">
                    <td colspan="3" style="border-bottom:1px solid black;">
                        The undersigned whose name and address are given on the item certify that the particulars given
                        in the declartion are correct and taht this item dose not contain any dangerous article or
                        articles pohibited by legislation or by postal or customs regulaitions
                    </td>

                </tr>
                <tr style="height:4.5mm;font-size: 11px;">
                    <td style="border-right:1px solid black;">
                        Signature： SLME
                    </td>
                    <td colspan="2">
                        DATE {{ date('Y-m-d') }}
                    </td>
                </tr>
            </table>
        </div>
        <div style="border-left:1px solid black;border-bottom:1px solid black;width:31.5mm;height:70mm;float:right;font-size: 12px;">
            <div style="font-weight: bold;margin-top:25px;"><p style="width:116px;float:left;">TO:</p></div>

            <p style="margin-right: 2px;">
                <b>
                    {{ $model->shipping_firstname . ' ' . $model->shipping_lastname }}
                </b>
                <br/>
                {{ $model->shipping_address }}<br/>
                {{ $model->shipping_address1 }}<br/>
                {{ $model->shipping_city . ' ' . $model->shipping_state }}<br/>
                {{ $model->shipping_zipcode }}<br/>
                Tel:{{ $model->shipping_phone }}<br/>
                {{ $model->country ? $model->country->name : '' }}<br/>
                {{ $model->country ? $model->country->cn_name : '' }}
            </p>
        </div>
    </div>
    <div style="border-top:1px solid black;height: 4mm;font-size: 12px;clear:both;">
        <p style="margin-left: 6px;width:50mm;float:left">RefNo:<b>{{ $model->order_id }}</b></p>
        <p style="margin-left: 6px;width:10mm;float:right">
            @if($model->order)
                @foreach($model->order->items as $item)
                    {{ $item->sku }} * {{ $item->quantity }} 【{{ $item->item ? ($item->item->warehousePosition ? $item->item->warehousePosition->name : '') : '' }}】
                @endforeach
            @endif
        </p>
    </div>
</div>
</body>
</html>