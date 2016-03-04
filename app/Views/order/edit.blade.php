@extends('common.form')
@section('formAction') {{ route('order.update', ['id' => $model->id]) }} @stop
@section('formBody')
    <input type="hidden" name="_method" value="PUT"/>
    <div class="form-group col-lg-2">
        <label for="channel_id" class='control-label'>渠道</label>
        <small class="text-danger glyphicon glyphicon-asterisk"></small>
        <input class="form-control" id="channel_id" placeholder="渠道" name='channel_id' value="{{ old('channel_id') ? old('channel_id') : $model->channel_id }}">
    </div>
    <div class="form-group col-lg-2">
        <label for="channel_account_id" class='control-label'>渠道账号</label>
        <small class="text-danger glyphicon glyphicon-asterisk"></small>
        <input class="form-control" id="channel_account_id" placeholder="渠道账号" name='channel_account_id' value="{{ old('channel_account_id') ? old('channel_account_id') : $model->channel_account_id }}">
    </div>
    <div class="form-group col-lg-2">
        <label for="order_number" class='control-label'>订单号</label>
        <small class="text-danger glyphicon glyphicon-asterisk"></small>
        <input class="form-control" id="order_number" placeholder="订单号" name='order_number' value="{{ old('order_number') ? old('order_number') : $model->order_number }}">
    </div>
    <div class="form-group col-lg-2">
        <label for="channel_order_number" class='control-label'>渠道订单号</label>
        <small class="text-danger glyphicon glyphicon-asterisk"></small>
        <input class="form-control" id="channel_order_number" placeholder="渠道订单号" name='channel_order_number' value="{{ old('channel_order_number') ? old('channel_order_number') : $model->channel_order_number }}">
    </div>
    <div class="form-group col-lg-2">
        <label for="email" class='control-label'>邮箱</label>
        <small class="text-danger glyphicon glyphicon-asterisk"></small>
        <input class="form-control" id="email" placeholder="邮箱" name='email' value="{{ old('email') ? old('email') : $model->email }}">
    </div>
    <div class="form-group col-lg-2">
        <label for="status" class='control-label'>订单状态</label>
        <small class="text-danger glyphicon glyphicon-asterisk"></small>
        <input class="form-control" id="status" placeholder="订单状态" name='status' value="{{ old('status') ? old('status') : $model->status }}">
    </div>
    <div class="form-group col-lg-2">
        <label for="active" class='control-label'>售后状态</label>
        <small class="text-danger glyphicon glyphicon-asterisk"></small>
        <input class="form-control" id="active" placeholder="售后状态" name='active' value="{{ old('active') ? old('active') : $model->active }}">
    </div>
    <div class="form-group col-lg-2">
        <label for="amount" class='control-label'>收款金额</label>
        <small class="text-danger glyphicon glyphicon-asterisk"></small>
        <input class="form-control" id="amount" placeholder="收款金额" name='amount' value="{{ old('amount') ? old('amount') : $model->amount }}">
    </div>
    <div class="form-group col-lg-2">
        <label for="amount_product" class='control-label'>产品金额</label>
        <small class="text-danger glyphicon glyphicon-asterisk"></small>
        <input class="form-control" id="amount_product" placeholder="产品金额" name='amount_product' value="{{ old('amount_product') ? old('amount_product') : $model->amount_product }}">
    </div>
    <div class="form-group col-lg-2">
        <label for="amount_shipping" class='control-label'>订单运费</label>
        <small class="text-danger glyphicon glyphicon-asterisk"></small>
        <input class="form-control" id="amount_shipping" placeholder="订单运费" name='amount_shipping' value="{{ old('amount_shipping') ? old('amount_shipping') : $model->amount_shipping }}">
    </div>
    <div class="form-group col-lg-2">
        <label for="amount_coupon" class='control-label'>折扣金额</label>
        <small class="text-danger glyphicon glyphicon-asterisk"></small>
        <input class="form-control" id="amount_coupon" placeholder="折扣金额" name='amount_coupon' value="{{ old('amount_coupon') ? old('amount_coupon') : $model->amount_coupon }}">
    </div>
    <div class="form-group col-lg-2">
        <label for="is_partial" class='control-label'>是否分批发货</label>
        <small class="text-danger glyphicon glyphicon-asterisk"></small>
        <input class="form-control" id="is_partial" placeholder="是否分批发货" name='is_partial' value="{{ old('is_partial') ? old('is_partial') : $model->is_partial }}">
    </div>
    <div class="form-group col-lg-2">
        <label for="by_hand" class='control-label'>是否手工</label>
        <small class="text-danger glyphicon glyphicon-asterisk"></small>
        <input class="form-control" id="by_hand" placeholder="是否手工" name='by_hand' value="{{ old('by_hand') ? old('by_hand') : $model->by_hand }}">
    </div>
    <div class="form-group col-lg-2">
        <label for="is_affair" class='control-label'>是否做账</label>
        <small class="text-danger glyphicon glyphicon-asterisk"></small>
        <input class="form-control" id="is_affair" placeholder="是否做账" name='is_affair' value="{{ old('is_affair') ? old('is_affair') : $model->is_affair }}">
    </div>
    <div class="form-group col-lg-2">
        <label for="affairer" class='control-label'>做账人员</label>
        <small class="text-danger glyphicon glyphicon-asterisk"></small>
        <input class="form-control" id="affairer" placeholder="做账人员" name='affairer' value="{{ old('affairer') ? old('affairer') : $model->affairer }}">
    </div>
    <div class="form-group col-lg-2">
        <label for="customer_service" class='control-label'>客服人员</label>
        <small class="text-danger glyphicon glyphicon-asterisk"></small>
        <input class="form-control" id="customer_service" placeholder="客服人员" name='customer_service' value="{{ old('customer_service') ? old('customer_service') : $model->customer_service }}">
    </div>
    <div class="form-group col-lg-2">
        <label for="operator" class='control-label'>运营人员</label>
        <small class="text-danger glyphicon glyphicon-asterisk"></small>
        <input class="form-control" id="operator" placeholder="运营人员" name='operator' value="{{ old('operator') ? old('operator') : $model->operator }}">
    </div>
    <div class="form-group col-lg-2">
        <label for="payment" class='control-label'>支付方式</label>
        <small class="text-danger glyphicon glyphicon-asterisk"></small>
        <input class="form-control" id="payment" placeholder="支付方式" name='payment' value="{{ old('payment') ? old('payment') : $model->payment }}">
    </div>
    <div class="form-group col-lg-2">
        <label for="currency" class='control-label'>币种</label>
        <small class="text-danger glyphicon glyphicon-asterisk"></small>
        <input class="form-control" id="currency" placeholder="币种" name='currency' value="{{ old('currency') ? old('currency') : $model->currency }}">
    </div>
    <div class="form-group col-lg-2">
        <label for="rate" class='control-label'>汇率</label>
        <small class="text-danger glyphicon glyphicon-asterisk"></small>
        <input class="form-control" id="rate" placeholder="汇率" name='rate' value="{{ old('rate') ? old('rate') : $model->rate }}">
    </div>
    <div class="form-group col-lg-2">
        <label for="ip" class='control-label'>IP地址</label>
        <small class="text-danger glyphicon glyphicon-asterisk"></small>
        <input class="form-control" id="ip" placeholder="IP地址" name='ip' value="{{ old('ip') ? old('ip') : $model->ip }}">
    </div>
    <div class="form-group col-lg-2">
        <label for="address_confirm" class='control-label'>地址验证</label>
        <small class="text-danger glyphicon glyphicon-asterisk"></small>
        <input class="form-control" id="address_confirm" placeholder="地址验证" name='address_confirm' value="{{ old('address_confirm') ? old('address_confirm') : $model->address_confirm }}">
    </div>
    <div class="form-group col-lg-2">
        <label for="comment" class='control-label'>备用字段</label>
        <small class="text-danger glyphicon glyphicon-asterisk"></small>
        <input class="form-control" id="comment" placeholder="备用字段" name='comment' value="{{ old('comment') ? old('comment') : $model->comment }}">
    </div>
    <div class="form-group col-lg-2">
        <label for="comment1" class='control-label'>红人/choies用</label>
        <small class="text-danger glyphicon glyphicon-asterisk"></small>
        <input class="form-control" id="comment1" placeholder="红人/choies用" name='comment1' value="{{ old('comment1') ? old('comment1') : $model->comment1 }}">
    </div>
    <div class="form-group col-lg-2">
        <label for="remark" class='control-label'>订单备注</label>
        <small class="text-danger glyphicon glyphicon-asterisk"></small>
        <input class="form-control" id="remark" placeholder="订单备注" name='remark' value="{{ old('remark') ? old('remark') : $model->remark }}">
    </div>
    <div class="form-group col-lg-2">
        <label for="import_remark" class='control-label'>导单备注</label>
        <small class="text-danger glyphicon glyphicon-asterisk"></small>
        <input class="form-control" id="import_remark" placeholder="导单备注" name='import_remark' value="{{ old('import_remark') ? old('import_remark') : $model->import_remark }}">
    </div>
    <div class="form-group col-lg-2">
        <label for="shipping" class='control-label'>种类</label>
        <small class="text-danger glyphicon glyphicon-asterisk"></small>
        <input class="form-control" id="shipping" placeholder="种类" name='shipping' value="{{ old('shipping') ? old('shipping') : $model->shipping }}">
    </div>
    <div class="form-group col-lg-2">
        <label for="shipping_firstname" class='control-label'>发货名字</label>
        <small class="text-danger glyphicon glyphicon-asterisk"></small>
        <input class="form-control" id="shipping_firstname" placeholder="发货名字" name='shipping_firstname' value="{{ old('shipping_firstname') ? old('shipping_firstname') : $model->shipping_firstname }}">
    </div>
    <div class="form-group col-lg-2">
        <label for="shipping_lastname" class='control-label'>发货姓氏</label>
        <small class="text-danger glyphicon glyphicon-asterisk"></small>
        <input class="form-control" id="shipping_lastname" placeholder="发货姓氏" name='shipping_lastname' value="{{ old('shipping_lastname') ? old('shipping_lastname') : $model->shipping_lastname }}">
    </div>
    <div class="form-group col-lg-2">
        <label for="shipping_address" class='control-label'>发货地址</label>
        <small class="text-danger glyphicon glyphicon-asterisk"></small>
        <input class="form-control" id="shipping_address" placeholder="发货地址" name='shipping_address' value="{{ old('shipping_address') ? old('shipping_address') : $model->shipping_address }}">
    </div>
    <div class="form-group col-lg-2">
        <label for="shipping_address1" class='control-label'>发货地址1</label>
        <small class="text-danger glyphicon glyphicon-asterisk"></small>
        <input class="form-control" id="shipping_address1" placeholder="发货地址1" name='shipping_address1' value="{{ old('shipping_address1') ? old('shipping_address1') : $model->shipping_address1 }}">
    </div>
    <div class="form-group col-lg-2">
        <label for="shipping_city" class='control-label'>发货城市</label>
        <small class="text-danger glyphicon glyphicon-asterisk"></small>
        <input class="form-control" id="shipping_city" placeholder="发货城市" name='shipping_city' value="{{ old('shipping_city') ? old('shipping_city') : $model->shipping_city }}">
    </div>
    <div class="form-group col-lg-2">
        <label for="shipping_state" class='control-label'>发货省/州</label>
        <small class="text-danger glyphicon glyphicon-asterisk"></small>
        <input class="form-control" id="shipping_state" placeholder="发货省/州" name='shipping_state' value="{{ old('shipping_state') ? old('shipping_state') : $model->shipping_state }}">
    </div>
    <div class="form-group col-lg-2">
        <label for="shipping_country" class='control-label'>发货国家/地区</label>
        <small class="text-danger glyphicon glyphicon-asterisk"></small>
        <input class="form-control" id="shipping_country" placeholder="发货国家/地区" name='shipping_country' value="{{ old('shipping_country') ? old('shipping_country') : $model->shipping_country }}">
    </div>
    <div class="form-group col-lg-2">
        <label for="shipping_zipcode" class='control-label'>发货邮编</label>
        <small class="text-danger glyphicon glyphicon-asterisk"></small>
        <input class="form-control" id="shipping_zipcode" placeholder="发货邮编" name='shipping_zipcode' value="{{ old('shipping_zipcode') ? old('shipping_zipcode') : $model->shipping_zipcode }}">
    </div>
    <div class="form-group col-lg-2">
        <label for="shipping_phone" class='control-label'>发货电话</label>
        <small class="text-danger glyphicon glyphicon-asterisk"></small>
        <input class="form-control" id="shipping_phone" placeholder="发货电话" name='shipping_phone' value="{{ old('shipping_phone') ? old('shipping_phone') : $model->shipping_phone }}">
    </div>
    <div class="form-group col-lg-2">
        <label for="billing_firstname" class='control-label'>账单名字</label>
        <small class="text-danger glyphicon glyphicon-asterisk"></small>
        <input class="form-control" id="billing_firstname" placeholder="账单名字" name='billing_firstname' value="{{ old('billing_firstname') ? old('billing_firstname') : $model->billing_firstname }}">
    </div>
    <div class="form-group col-lg-2">
        <label for="billing_lastname" class='control-label'>账单姓氏</label>
        <small class="text-danger glyphicon glyphicon-asterisk"></small>
        <input class="form-control" id="billing_lastname" placeholder="账单姓氏" name='billing_lastname' value="{{ old('billing_lastname') ? old('billing_lastname') : $model->billing_lastname }}">
    </div>
    <div class="form-group col-lg-2">
        <label for="billing_address" class='control-label'>账单地址</label>
        <small class="text-danger glyphicon glyphicon-asterisk"></small>
        <input class="form-control" id="billing_address" placeholder="账单地址" name='billing_address' value="{{ old('billing_address') ? old('billing_address') : $model->billing_address }}">
    </div>
    <div class="form-group col-lg-2">
        <label for="billing_city" class='control-label'>账单城市</label>
        <small class="text-danger glyphicon glyphicon-asterisk"></small>
        <input class="form-control" id="billing_city" placeholder="账单城市" name='billing_city' value="{{ old('billing_city') ? old('billing_city') : $model->billing_city }}">
    </div>
    <div class="form-group col-lg-2">
        <label for="billing_state" class='control-label'>账单省/州</label>
        <small class="text-danger glyphicon glyphicon-asterisk"></small>
        <input class="form-control" id="billing_state" placeholder="账单省/州" name='billing_state' value="{{ old('billing_state') ? old('billing_state') : $model->billing_state }}">
    </div>
    <div class="form-group col-lg-2">
        <label for="billing_country" class='control-label'>账单国家/地区</label>
        <small class="text-danger glyphicon glyphicon-asterisk"></small>
        <input class="form-control" id="billing_country" placeholder="账单国家/地区" name='billing_country' value="{{ old('billing_country') ? old('billing_country') : $model->billing_country }}">
    </div>
    <div class="form-group col-lg-2">
        <label for="billing_zipcode" class='control-label'>账单邮编</label>
        <small class="text-danger glyphicon glyphicon-asterisk"></small>
        <input class="form-control" id="billing_zipcode" placeholder="账单邮编" name='billing_zipcode' value="{{ old('billing_zipcode') ? old('billing_zipcode') : $model->billing_zipcode }}">
    </div>
    <div class="form-group col-lg-2">
        <label for="billing_phone" class='control-label'>账单电话</label>
        <small class="text-danger glyphicon glyphicon-asterisk"></small>
        <input class="form-control" id="billing_phone" placeholder="账单电话" name='billing_phone' value="{{ old('billing_phone') ? old('billing_phone') : $model->billing_phone }}">
    </div>
    <div class="form-group col-lg-2">
        <label for="payment_date" class='control-label'>支付时间</label>
        <small class="text-danger glyphicon glyphicon-asterisk"></small>
        <input class="form-control" id="payment_date" placeholder="支付时间" name='payment_date' value="{{ old('payment_date') ? old('payment_date') : $model->payment_date }}">
    </div>
    <div class="form-group col-lg-2">
        <label for="affair_time" class='control-label'>做账时间</label>
        <small class="text-danger glyphicon glyphicon-asterisk"></small>
        <input class="form-control" id="affair_time" placeholder="做账时间" name='affair_time' value="{{ old('affair_time') ? old('affair_time') : $model->affair_time }}">
    </div>
    <div class="form-group col-lg-2">
        <label for="create_time" class='control-label'>定义时间</label>
        <small class="text-danger glyphicon glyphicon-asterisk"></small>
        <input class="form-control" id="create_time" placeholder="定义时间" name='create_time' value="{{ old('create_time') ? old('create_time') : $model->create_time }}">
    </div>
@stop