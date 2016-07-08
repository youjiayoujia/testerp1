<style>
    *{
        margin: 0;
        padding: 0;
    }
    body{
        font-family: Arial, Helvetica, sans-serif;
        font-size: 14px;
    }
    #main_frame_box{
        width: 382px;
        margin: 0 auto;
        height: 378px;
        overflow: hidden;
        margin-bottom: 2px;
    }
    td{
        border: 1px solid #000;
        border-bottom: none;
    }
</style>
<div id="main_frame_box">
    <div style="width:380px;height:150px;border:1px solid #000;border-bottom:none;">
        <p style="float:left;width:140px;height:30px;line-height:30px;text-align:center;font-size:12px;border-right:1px solid #000;"><img src="" /></p>
        <p style="float:left;width:80px;height:30px;text-align:center;font-size:12px;border-right:1px solid #000;">Small Packet By Air</p>
        <p style="float:left;width:40px;height:30px;line-height:30px;text-align:center;font-weight:bold;border-right:1px solid #000;">{{ $model->shipping_country }}{{ $model->country ? $model->country->id : '' }}</p>
        <p style="float:left;width:100px;height:30px;line-height:30px;text-align:center;font-weight:bold;font-size:14px;">{{ $model->order ? $model->order->channel_ordernum : '' }}</p>
        <p style="float:left;width:140px;height:90px;border:1px solid #000;border-right:none;font-family:STHeiti;font-size:12px;">
            From:<br/>
            (Chen Yuelei)No121<br/>
            Longpan Road,Xuanwu<br/>
            Dirstrict,Nanjing<br/>
            Jiangsu CHINA<br/>
            Phone:13918244035<br/>
        </p>
        <p style="float:left;width:235px;height:90px;border:1px solid #000;border-right:none;font-family:STHeiti;font-size:12px;">
            Weber Billie<br/>
            13 Rue Cap Gos<br/>
            Thezan Les Benziers<br/>
            Languodoc Roussillon<br/>
            34490<br/>
            France<br/>
            Phone：0033607152794<br/>
        </p>
        <p style="float:left;width:141px;height:30px;font-size:10px;border-right:1px solid #000;">自编号:{{ $model->order ? $model->order->ordernum : '' }}</p>
        <p style="float:left;width:120px;height:30px;font-size:10px;text-align:center;border-right:1px solid #000;">4</p>
        <p style="float:left;width:114px;height:30px;font-size:10px;text-align:center;">{{ $model->country ? $model->country->cn_name : '' }}</p>
    </div>
    <table style="width:381px;height:155px;border: 1px;" cellspacing="0" cellpadding="0">
        <tr style="height: 55px;">
            <td colspan="3">
                <p style="width:86px;text-align:center;font-weight:bold;line-height:50px;height:50px;float:left;">
                    Untracked
                </p>
                <p style="width:270px;height:45px;line-height:45px;float:left;text-align:center;">
                    70502003051
                </p>
            </td>
        </tr>
        <tr style="height: 15px;">
            <td colspan="3" style="font-size: 10px;">
                退件单位:南京邮政局函件广告局&nbsp;&nbsp;(PD)
            </td>
        </tr>
        <tr style="height: 15px;font-size: 10px;text-align: center;">
            <td width="70%" style="border-right:none;">
                Description of Contents
            </td>
            <td width="15%" style="border-right:none;">
                Kg
            </td>
            <td width="15%">
                Val(US $)
            </td>
        </tr>
        <tr style="height: 15px;border-right: none;font-size: 12px;text-align: center">
            <td width="70%" style="border-right:none;">Totalg Gross Weight(kg)</td>
            <td width="15%" style="border-right:none;">0.072</td>
            <td width="15%">2.1</td>
        </tr>
        <tr style="height: 35px;">
            <td colspan="3" style="border-bottom:1px solid #000;font-size:9px;">
                I certify that the particulars given in this declaration are correct and this item does not contain any dangerous
                articles prohibited by legislation or by postal or customers regulations.<br/>
            </td>
        </tr>
        <tr style="height:15px;">
            <td width="25%" style="font-size:11px;text-align:left;">Sender's signiture:SLME</td>
            <td width="15%" style="font-size:11px;text-align:center;">CN22</td>
            <td width="60%" style="font-size:11px;text-align:center;">{{ date('Y-m-d H:i') }}</td>
        </tr>
    </table>
    <div style="width:382px;height:40px;margin:0 auto;font-size:10px;white-space:normal;overflow:hidden;">
        @if($model->order)
            @foreach($model->order->items as $item)
                {{ $item->sku }} * {{ $item->quantity }}
            @endforeach
        @endif
        <b style="font-size:11px;">【.39-03.E01】</b>
    </div>
</div>