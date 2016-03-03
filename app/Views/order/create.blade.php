@extends('common.form')

<script src="{{ asset('js/jquery.min.js') }}"></script>{{-- JQuery JS --}}
@section('formAction') {{ route('order.store') }} @stop
@section('formBody')
    <div class="panel panel-default">
        <div class="panel-heading">基础信息</div>
        <div class="panel-body">
            <div class="form-group col-lg-2">
                <label for="channel_account_id" class='control-label'>渠道类型</label>
                <small class="text-danger glyphicon glyphicon-asterisk"></small>
                <input class="form-control" id="channel_account_id" placeholder="渠道类型" name='channel_account_id' value="{{ old('channel_account_id') }}">
            </div>
            <div class="form-group col-lg-2">
                <label for="channel_id" class='control-label'>渠道代码</label>
                <small class="text-danger glyphicon glyphicon-asterisk"></small>
                <input class="form-control" id="channel_id" placeholder="渠道代码" name='channel_id' value="{{ old('channel_id') }}">
            </div>
            <div class="form-group col-lg-2">
                <label for="order_number" class='control-label'>订单号</label>
                <small class="text-danger glyphicon glyphicon-asterisk"></small>
                <input class="form-control" id="order_number" placeholder="订单号" name='order_number' value="{{ old('order_number') }}">
            </div>
            <div class="form-group col-lg-2">
                <label for="customer_order_number" class='control-label'>批发订单号</label>
                <small class="text-danger glyphicon glyphicon-asterisk"></small>
                <input class="form-control" id="customer_order_number" placeholder="批发订单号" name='customer_order_number' value="{{ old('customer_order_number') }}">
            </div>
            <div class="form-group col-lg-2">
                <label for="email" class='control-label'>邮箱</label>
                <small class="text-danger glyphicon glyphicon-asterisk"></small>
                <input class="form-control" id="email" placeholder="邮箱" name='email' value="{{ old('email') }}">
            </div>
            <div class="form-group col-lg-2">
                <label for="status" class='control-label'>订单状态</label>
                <small class="text-danger glyphicon glyphicon-asterisk"></small>
                <input class="form-control" id="status" placeholder="订单状态" name='status' value="{{ old('status') }}">
            </div>
            <div class="form-group col-lg-2">
                <label for="active" class='control-label'>售后状态</label>
                <small class="text-danger glyphicon glyphicon-asterisk"></small>
                <input class="form-control" id="active" placeholder="售后状态" name='active' value="{{ old('active') }}">
            </div>

            <div class="form-group col-lg-2">
                <label for="is_partial" class='control-label'>是否分批发货</label>
                <small class="text-danger glyphicon glyphicon-asterisk"></small>
                <input class="form-control" id="is_partial" placeholder="是否分批发货" name='is_partial' value="{{ old('is_partial') }}">
            </div>
            <div class="form-group col-lg-2">
                <label for="by_hand" class='control-label'>是否手工</label>
                <small class="text-danger glyphicon glyphicon-asterisk"></small>
                <input class="form-control" id="by_hand" placeholder="是否手工" name='by_hand' value="{{ old('by_hand') }}">
            </div>
            <div class="form-group col-lg-2">
                <label for="is_affair" class='control-label'>是否做账</label>
                <small class="text-danger glyphicon glyphicon-asterisk"></small>
                <input class="form-control" id="is_affair" placeholder="是否做账" name='is_affair' value="{{ old('is_affair') }}">
            </div>
            <div class="form-group col-lg-2">
                <label for="affairer" class='control-label'>做账人员</label>
                <small class="text-danger glyphicon glyphicon-asterisk"></small>
                <input class="form-control" id="affairer" placeholder="做账人员" name='affairer' value="{{ old('affairer') }}">
            </div>
            <div class="form-group col-lg-2">
                <label for="customer_service" class='control-label'>客服人员</label>
                <small class="text-danger glyphicon glyphicon-asterisk"></small>
                <input class="form-control" id="customer_service" placeholder="客服人员" name='customer_service' value="{{ old('customer_service') }}">
            </div>
            <div class="form-group col-lg-2">
                <label for="operator" class='control-label'>运营人员</label>
                <small class="text-danger glyphicon glyphicon-asterisk"></small>
                <input class="form-control" id="operator" placeholder="运营人员" name='operator' value="{{ old('operator') }}">
            </div>
            <div class="form-group col-lg-2">
                <label for="ip" class='control-label'>IP地址</label>
                <small class="text-danger glyphicon glyphicon-asterisk"></small>
                <input class="form-control" id="ip" placeholder="IP地址" name='ip' value="{{ old('ip') }}">
            </div>
            <div class="form-group col-lg-2">
                <label for="address_confirm" class='control-label'>地址验证</label>
                <small class="text-danger glyphicon glyphicon-asterisk"></small>
                <input class="form-control" id="address_confirm" placeholder="地址验证" name='address_confirm' value="{{ old('address_confirm') }}">
            </div>
            <div class="form-group col-lg-2">
                <label for="comment" class='control-label'>备用字段</label>
                <small class="text-danger glyphicon glyphicon-asterisk"></small>
                <input class="form-control" id="comment" placeholder="备用字段" name='comment' value="{{ old('comment') }}">
            </div>
            <div class="form-group col-lg-2">
                <label for="comment1" class='control-label'>红人/choies用</label>
                <small class="text-danger glyphicon glyphicon-asterisk"></small>
                <input class="form-control" id="comment1" placeholder="红人/choies用" name='comment1' value="{{ old('comment1') }}">
            </div>
            <div class="form-group col-lg-2">
                <label for="affair_time" class='control-label'>做账时间</label>
                <small class="text-danger glyphicon glyphicon-asterisk"></small>
                <input class="form-control" id="affair_time" placeholder="做账时间" name='affair_time' value="{{ old('affair_time') }}">
            </div>
            <div class="form-group col-lg-2">
                <label for="create_time" class='control-label'>定义时间</label>
                <small class="text-danger glyphicon glyphicon-asterisk"></small>
                <input class="form-control" id="create_time" placeholder="定义时间" name='create_time' value="{{ old('create_time') }}">
            </div>
            <div class="form-group col-lg-2">
                <label for="remark" class='control-label'>订单备注</label>
                <small class="text-danger glyphicon glyphicon-asterisk"></small>
                <input class="form-control" id="remark" placeholder="订单备注" name='remark' value="{{ old('remark') }}">
            </div>
            <div class="form-group col-lg-2">
                <label for="import_remark" class='control-label'>导单备注</label>
                <small class="text-danger glyphicon glyphicon-asterisk"></small>
                <input class="form-control" id="import_remark" placeholder="导单备注" name='import_remark' value="{{ old('import_remark') }}">
            </div>
        </div>
    </div>
    <div class="panel panel-default">
        <div class="panel-heading">金额</div>
        <div class="panel-body">
            <div class="form-group col-lg-2">
                <label for="currency" class='control-label'>币种</label>
                <small class="text-danger glyphicon glyphicon-asterisk"></small>
                <input class="form-control" id="currency" placeholder="币种" name='currency' value="{{ old('currency') }}">
            </div>
            <div class="form-group col-lg-2">
                <label for="rate" class='control-label'>汇率</label>
                <small class="text-danger glyphicon glyphicon-asterisk"></small>
                <input class="form-control" id="rate" placeholder="汇率" name='rate' value="{{ old('rate') }}">
            </div>
            <div class="form-group col-lg-2">
                <label for="amount" class='control-label'>收款金额</label>
                <small class="text-danger glyphicon glyphicon-asterisk"></small>
                <input class="form-control" id="amount" placeholder="收款金额" name='amount' value="{{ old('amount') }}">
            </div>
            <div class="form-group col-lg-2">
                <label for="amount_product" class='control-label'>产品金额</label>
                <small class="text-danger glyphicon glyphicon-asterisk"></small>
                <input class="form-control" id="amount_product" placeholder="产品金额" name='amount_product' value="{{ old('amount_product') }}">
            </div>
            <div class="form-group col-lg-2">
                <label for="amount_shipping" class='control-label'>订单运费</label>
                <small class="text-danger glyphicon glyphicon-asterisk"></small>
                <input class="form-control" id="amount_shipping" placeholder="订单运费" name='amount_shipping' value="{{ old('amount_shipping') }}">
            </div>
            <div class="form-group col-lg-2">
                <label for="amount_coupon" class='control-label'>折扣金额</label>
                <small class="text-danger glyphicon glyphicon-asterisk"></small>
                <input class="form-control" id="amount_coupon" placeholder="折扣金额" name='amount_coupon' value="{{ old('amount_coupon') }}">
            </div>
        </div>
    </div>
    <div class="panel panel-default">
        <div class="panel-heading">物流信息</div>
        <div class="panel-body">
            <div class="form-group col-lg-2">
                <label for="shipping" class='control-label'>种类</label>
                <small class="text-danger glyphicon glyphicon-asterisk"></small>
                <input class="form-control" id="shipping" placeholder="种类" name='shipping' value="{{ old('shipping') }}">
            </div>
            <div class="form-group col-lg-2">
                <label for="shipping_firstname" class='control-label'>发货名字</label>
                <small class="text-danger glyphicon glyphicon-asterisk"></small>
                <input class="form-control" id="shipping_firstname" placeholder="发货名字" name='shipping_firstname' value="{{ old('shipping_firstname') }}">
            </div>
            <div class="form-group col-lg-2">
                <label for="shipping_lastname" class='control-label'>发货姓氏</label>
                <small class="text-danger glyphicon glyphicon-asterisk"></small>
                <input class="form-control" id="shipping_lastname" placeholder="发货姓氏" name='shipping_lastname' value="{{ old('shipping_lastname') }}">
            </div>
            <div class="form-group col-lg-2">
                <label for="shipping_address" class='control-label'>发货地址</label>
                <small class="text-danger glyphicon glyphicon-asterisk"></small>
                <input class="form-control" id="shipping_address" placeholder="发货地址" name='shipping_address' value="{{ old('shipping_address') }}">
            </div>
            <div class="form-group col-lg-2">
                <label for="shipping_address1" class='control-label'>发货地址1</label>
                <small class="text-danger glyphicon glyphicon-asterisk"></small>
                <input class="form-control" id="shipping_address1" placeholder="发货地址1" name='shipping_address1' value="{{ old('shipping_address1') }}">
            </div>
            <div class="form-group col-lg-2">
                <label for="shipping_city" class='control-label'>发货城市</label>
                <small class="text-danger glyphicon glyphicon-asterisk"></small>
                <input class="form-control" id="shipping_city" placeholder="发货城市" name='shipping_city' value="{{ old('shipping_city') }}">
            </div>
            <div class="form-group col-lg-2">
                <label for="shipping_state" class='control-label'>发货省/州</label>
                <small class="text-danger glyphicon glyphicon-asterisk"></small>
                <input class="form-control" id="shipping_state" placeholder="发货省/州" name='shipping_state' value="{{ old('shipping_state') }}">
            </div>
            <div class="form-group col-lg-2">
                <label for="shipping_country" class='control-label'>发货国家/地区</label>
                <small class="text-danger glyphicon glyphicon-asterisk"></small>
                <input class="form-control" id="shipping_country" placeholder="发货国家/地区" name='shipping_country' value="{{ old('shipping_country') }}">
            </div>
            <div class="form-group col-lg-2">
                <label for="shipping_zipcode" class='control-label'>发货邮编</label>
                <small class="text-danger glyphicon glyphicon-asterisk"></small>
                <input class="form-control" id="shipping_zipcode" placeholder="发货邮编" name='shipping_zipcode' value="{{ old('shipping_zipcode') }}">
            </div>
            <div class="form-group col-lg-2">
                <label for="shipping_phone" class='control-label'>发货电话</label>
                <small class="text-danger glyphicon glyphicon-asterisk"></small>
                <input class="form-control" id="shipping_phone" placeholder="发货电话" name='shipping_phone' value="{{ old('shipping_phone') }}">
            </div>
        </div>
    </div>
    <div class="panel panel-default">
        <div class="panel-heading">支付信息</div>
        <div class="panel-body">
            <div class="form-group col-lg-2">
                <label for="payment" class='control-label'>支付方式</label>
                <small class="text-danger glyphicon glyphicon-asterisk"></small>
                <input class="form-control" id="payment" placeholder="支付方式" name='payment' value="{{ old('payment') }}">
            </div>
            <div class="form-group col-lg-2">
                <label for="billing_firstname" class='control-label'>账单名字</label>
                <small class="text-danger glyphicon glyphicon-asterisk"></small>
                <input class="form-control" id="billing_firstname" placeholder="账单名字" name='billing_firstname' value="{{ old('billing_firstname') }}">
            </div>
            <div class="form-group col-lg-2">
                <label for="billing_lastname" class='control-label'>账单姓氏</label>
                <small class="text-danger glyphicon glyphicon-asterisk"></small>
                <input class="form-control" id="billing_lastname" placeholder="账单姓氏" name='billing_lastname' value="{{ old('billing_lastname') }}">
            </div>
            <div class="form-group col-lg-2">
                <label for="billing_address" class='control-label'>账单地址</label>
                <small class="text-danger glyphicon glyphicon-asterisk"></small>
                <input class="form-control" id="billing_address" placeholder="账单地址" name='billing_address' value="{{ old('billing_address') }}">
            </div>
            <div class="form-group col-lg-2">
                <label for="billing_city" class='control-label'>账单城市</label>
                <small class="text-danger glyphicon glyphicon-asterisk"></small>
                <input class="form-control" id="billing_city" placeholder="账单城市" name='billing_city' value="{{ old('billing_city') }}">
            </div>
            <div class="form-group col-lg-2">
                <label for="billing_state" class='control-label'>账单省/州</label>
                <small class="text-danger glyphicon glyphicon-asterisk"></small>
                <input class="form-control" id="billing_state" placeholder="账单省/州" name='billing_state' value="{{ old('billing_state') }}">
            </div>
            <div class="form-group col-lg-2">
                <label for="billing_country" class='control-label'>账单国家/地区</label>
                <small class="text-danger glyphicon glyphicon-asterisk"></small>
                <input class="form-control" id="billing_country" placeholder="账单国家/地区" name='billing_country' value="{{ old('billing_country') }}">
            </div>
            <div class="form-group col-lg-2">
                <label for="billing_zipcode" class='control-label'>账单邮编</label>
                <small class="text-danger glyphicon glyphicon-asterisk"></small>
                <input class="form-control" id="billing_zipcode" placeholder="账单邮编" name='billing_zipcode' value="{{ old('billing_zipcode') }}">
            </div>
            <div class="form-group col-lg-2">
                <label for="billing_phone" class='control-label'>账单电话</label>
                <small class="text-danger glyphicon glyphicon-asterisk"></small>
                <input class="form-control" id="billing_phone" placeholder="账单电话" name='billing_phone' value="{{ old('billing_phone') }}">
            </div>
            <div class="form-group col-lg-2">
                <label for="payment_date" class='control-label'>支付时间</label>
                <small class="text-danger glyphicon glyphicon-asterisk"></small>
                <input class="form-control" id="payment_date" placeholder="支付时间" name='payment_date' value="{{ old('payment_date') }}">
            </div>
        </div>
    </div>
    <div class="panel panel-default">
        <div class="panel-heading">产品信息</div>
        <div class="panel-body">
        </div>
    </div>
@stop
<script type="text/javascript">
    var date = new Date();
    var year = date.getFullYear();
    var mon = date.getMonth() + 1;
    var day = date.getDate();
    var nowDay = year + "-" + (mon < 10 ? "0" + mon : mon) + "-" + (day < 10 ? "0" + day : day);
    $('#create_time').val(nowDay);
</script>